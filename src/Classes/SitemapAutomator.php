<?php

namespace Anker\ModulesBundle\Classes;

use Contao\Automator as ContaoAutomator;

/**
 * Class Automator
 */
class SitemapAutomator extends ContaoAutomator
{
	public function generateSitemap($intId = 0)
	{
		$time = \Date::floorToMinute();
		$objDatabase = \Database::getInstance();

		$this->purgeXmlFiles();

		// Only root pages should have sitemap names
		$objDatabase->execute("UPDATE tl_page SET createSitemap='', sitemapName='' WHERE type!='root'");
		// Get a particular root page
		if ($intId > 0) {
			do {
				$objRoot = $objDatabase->prepare("SELECT * FROM tl_page WHERE id=?")
									   ->limit(1)
									   ->execute($intId);
				if ($objRoot->numRows < 1) {
					break;
				}
				$intId = $objRoot->pid;
			} while ($objRoot->type != 'root' && $intId > 0);
			// Make sure the page is published
			if (!$objRoot->published || ($objRoot->start != '' && $objRoot->start > $time) || ($objRoot->stop != '' && $objRoot->stop <= ($time + 60))) {
				return;
			}
			// Check the sitemap name
			if (!$objRoot->createSitemap || !$objRoot->sitemapName) {
				return;
			}
			$objRoot->reset();
		} else { // Get all published root pages
			$objRoot = $objDatabase->execute("SELECT id, language, sitemapName FROM tl_page WHERE type='root' AND createSitemap='1' AND sitemapName!='' AND (start='' OR start<='$time') AND (stop='' OR stop>'" . ($time + 60) . "') AND published='1'");
		}
		// Return if there are no pages
		if ($objRoot->numRows < 1) {
			return;
		}
		// Create the XML file
		while ($objRoot->next()) {
			$objFile = new \File(\StringUtil::stripRootDir(\System::getContainer()->getParameter('contao.web_dir')) . '/share/' . $objRoot->sitemapName . '.xml');
			$objFile->truncate();
			$objFile->append('<?xml version="1.0" encoding="UTF-8"?>');
			$objFile->append('<?xml-stylesheet type="text/xsl" href="/sitemap.xsl"?>');
			$objFile->append('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1">');
			// Find the searchable pages

			$arrPages = \Backend::findSearchablePages($objRoot->id, '', true);

			// HOOK: take additional pages
			if (isset($GLOBALS['TL_HOOKS']['getSearchablePages']) && \is_array($GLOBALS['TL_HOOKS']['getSearchablePages'])) {
				foreach ($GLOBALS['TL_HOOKS']['getSearchablePages'] as $callback) {
					$this->import($callback[0]);
					$arrPages = $this->{$callback[0]}->{$callback[1]}($arrPages, $objRoot->id, true, $objRoot->language);
				}
			}

			$arrPagesWithOptions = Sitemap::findSearchablePagesWithOptions($objRoot->id, true, $objRoot->language);

			// HOOK: take additional pages with options (new extension hook)
			if (isset($GLOBALS['TL_HOOKS']['getSearchablePagesWithOptions']) && is_array($GLOBALS['TL_HOOKS']['getSearchablePagesWithOptions'])) {
				foreach ($GLOBALS['TL_HOOKS']['getSearchablePagesWithOptions'] as $callback) {
					$this->import($callback[0]);
					$arrPagesWithOptions = $this->{$callback[0]}->{$callback[1]}($arrPagesWithOptions, $objRoot->id, true, $objRoot->language);
				}
			}

			if (isset($arrPages) && is_array($arrPages)) {
				foreach ($arrPages as $strUrl) {
					if (!isset($arrPagesWithOptions[$strUrl])) {
						$arrPagesWithOptions[] = ['url' => $strUrl];
					}
				}
			}

			foreach ($arrPagesWithOptions as $k) {
				$objFile->append('  <url>');

				$objFile->append('    <loc>' . $k['url'] . '</loc>');

				// language relations
				if (isset($k['id']) && in_array('hofff_language_relations', \Config::getActiveModules())) {
					$arrPageLanguageRelations = SitemapLanguageRelations::findPageLanguageRelations($k['id'], $objRoot->language);
				}

				if ($arrPageLanguageRelations != null) {
					foreach ($arrPageLanguageRelations as $relation) {
						$objFile->append('    <xhtml:link rel="alternate" hreflang="' . $relation['language'] . '" href="' . $relation['url'] . '" />');
					}

					$objFile->append('    <xhtml:link rel="alternate" hreflang="' . $objRoot->language . '" href="' . $k['url'] . '" />');
				}

				if (isset($k['lastmod']) && !empty($k['lastmod'])) {
					$objFile->append('    <lastmod>' . $k['lastmod'] . '</lastmod>');
				} else {
					$objFile->append('    <lastmod>'.date('Y-m-').'01</lastmod>');
				}

				if (isset($k['changefreq']) && !empty($k['changefreq'])) {
					$objFile->append('    <changefreq>' . $k['changefreq'] . '</changefreq>');
				} else {
					$objFile->append('    <changefreq>monthly</changefreq>');
				}

				if (isset($k['priority']) && !empty($k['priority'])) {
					$objFile->append('    <priority>' . $k['priority'] . '</priority>');
				} else {
					$objFile->append('    <priority>0.5</priority>');
				}

				if (isset($k['ce']) && !empty($k['ce'])) {
					foreach ($k['ce'] as $ce) {
						if ($ce['ceType'] == 'image') {
							$objFile->append('    <image:image>');
							$objFile->append('      <image:loc>' . $ce['ceUrl'] . '</image:loc>');

							if (isset($ce['ceTitle']) && !empty($ce['ceTitle'])) {
								$objFile->append('      <image:title>' . htmlspecialchars($ce['ceTitle']) . '</image:title>');
							}

							if (isset($ce['ceCaption']) && !empty($ce['ceCaption'])) {
								$objFile->append('      <image:caption>' . htmlspecialchars($ce['ceCaption']) . '</image:caption>');
							}

							$objFile->append('    </image:image>');
						} elseif ($ce['ceType'] == 'downloads') {
							foreach ($ce['ceUrl'] as $k) {
								$objFile->append('  </url>');
								$objFile->append('  <url>');
								$objFile->append('    <loc>' . $k . '</loc>');
							}
						} else {
							$objFile->append('  </url>');
							$objFile->append('  <url>');
							$objFile->append('    <loc>' . $ce['ceUrl'] . '</loc>');
						}
					}
				}

				if (isset($k['articles']) && !empty($k['articles'])) {
					foreach ($k['articles'] as $arrArticle) {
						$objFile->append('  </url>');
						$objFile->append('  <url>');
						$objFile->append('    <loc>' . $arrArticle['url'] . '</loc>');
						$objFile->append('    <changefreq>' . $arrArticle['changefreq'] . '</changefreq>');
						$objFile->append('    <lastmod>' . $arrArticle['lastmod'] . '</lastmod>');
						$objFile->append('    <priority>' . $arrArticle['priority'] . '</priority>');
					}
				}

				$objFile->append('  </url>');
			}

			$objFile->append('</urlset>');
			$objFile->close();

			$this->log('Generated sitemap "' . $objRoot->sitemapName . '.xml (gSitemap)"', __METHOD__, TL_CRON);
		}

		if (\Config::get('activateSitemapIndex') == true) {
			Sitemap::buildSitemapIndex();

			$this->log('Generated sitemapIndex "' . \Config::get('sitemapIndexName') . '.xml" (gSitemap)', __METHOD__, TL_CRON);
		}

		/* Leave this out for now...
		if (\Config::get('sitemapPingGoogleBing') == true)
		{
			if ($objRoot->inSitemapIndex == true)
			{
				Sitemap::pingGoogleBing($objRoot->id);
			}
		}
		*/
	}
}
