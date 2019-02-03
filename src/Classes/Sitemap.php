<?php

namespace Anker\ModulesBundle\Classes;

/**
 * Class extendedSitemap
 */
class Sitemap extends \Backend
{
	/**
	 * Only root pages should have sitemap names
	 */
	public function deleteSitemapNamesForNonRootPages()
	{
		$objDatabase = \Database::getInstance();

		$objDatabase->prepare("UPDATE tl_page SET createSitemap = '', sitemapName = '' WHERE type != 'root'")->execute();
	}


	/**
	 * Get all root pages
	 *
	 * @param array $arrOptions
	 *
	 * @return object
	 */
	public function findPublishedRootPages($arrOptions)
	{
		return \PageModel::findPublishedRootPages($arrOptions);
	}

	/**
	 * Get all searchable pages
	 *
	 * @param array $arrOptions
	 *
	 * @return object
	 */
	public static function findSearchablePages($pid = 0, $domain = '', $blnIsSitemap = false)
	{
		return parent::findSearchablePages($pid, $domain, $blnIsSitemap);
	}


	/**
	 * Get all searchable pages with options and files/images
	 *
	 * @param integer $pid
	 * @param boolean $blnIsSitemap
	 * @param string $ptable
	 * @param boolean $blnNewCycle
	 *
	 * @return array
	 */

	public static function findSearchablePagesWithOptions($pid = 0, $blnIsSitemap = false, $rootLanguage = null)
	{
		$time = \Date::floorToMinute();
		$objDatabase = \Database::getInstance();
		// Get published pages
		$objPages = $objDatabase->prepare("SELECT * FROM tl_page WHERE pid=? AND (start='' OR start<='$time') AND (stop='' OR stop>'" . ($time + 60) . "') AND published='1' ORDER BY sorting")
								->execute($pid);
		if ($objPages->numRows < 1) {
			return [];
		}

		$arrPages = [];
		$objRegistry = \Model\Registry::getInstance();
		// Recursively walk through all subpages
		while ($objPages->next()) {
			$objPage = $objRegistry->fetch('tl_page', $objPages->id);
			if ($objPage === null) {
				$objPage = new \PageModel($objPages);
			}
			if ($objPage->type == 'regular') {
				// Searchable and not protected
				if ((!$objPage->noSearch || $blnIsSitemap) && (!$objPage->protected || \Config::get('indexProtected') && (!$blnIsSitemap || $objPage->sitemap == 'map_always')) && (!$blnIsSitemap || $objPage->sitemap != 'map_never')) {
					// Published
					if ($objPage->published && ($objPage->start == '' || $objPage->start <= $time) && ($objPage->stop == '' || $objPage->stop > ($time + 60))) {
						$arrPage = [];

						$strPageUrl = $objPage->getAbsoluteUrl();
						$strPageUrl = rawurlencode($strPageUrl);
						$strPageUrl = str_replace(['%2F', '%3F', '%3D', '%26', '%3A//'], ['/', '?', '=', '&', '://'], $strPageUrl);
						$strPageUrl = ampersand($strPageUrl, true);

						$arrPage['id'] = $objPage->id;
						$arrPage['url'] = $strPageUrl;
						$arrPage['lastmod'] = $objPage->sitemapLastmodDate != '' ? date("Y-m-d", $objPage->sitemapLastmodDate) : date("Y-m-d", time());
						$arrPage['changefreq'] = $objPage->sitemapChangefreq;
						$arrPage['priority'] = $objPage->sitemapPriority != 0 ?  '0.' . $objPage->sitemapPriority : '1.0';

						if ($objFile = \FilesModel::findByUuid($objPage->teaserSRC)) {
							$arrPage['ce'][] = [
								'ceType' => 'image',
								'ceUrl' => \Environment::get('base') . $objFile->path,
								'ceTitle' => $objPage->title,
							];
						}

						if (!empty($objPage->import_data)) {
							$boatData = json_decode($objPage->import_data);
							$arrPage['ce'][] = [
								'ceType' => 'image',
								'ceUrl' => \Environment::get('base') . 'img' . $boatData->photo_url,
								'ceTitle' => $objPage->title,
								'ceCaption' => $objPage->title,
							];
						}

						// Get articles with teaser
						$objArticles = $objDatabase->prepare("SELECT * FROM tl_article WHERE pid=? AND (start='' OR start<='$time') AND (stop='' OR stop>'" . ($time + 60) . "') AND published='1' AND showTeaser='1' ORDER BY sorting")->execute($objPages->id);

						if ($objArticles->numRows) {
							while ($objArticles->next()) {
								$strAliasOrId = ($objArticles->alias != null && !\Config::get('disableAlias')) ? $objArticles->alias : $objArticles->id;

								$arrArticles['id'] = $objPage->id . '.' . $objArticles->id;
								$arrArticles['url'] = $strPageUrl . '/articles/' . $strAliasOrId;
								$arrArticles['lastmod'] = $objPage->sitemapLastmodDate != '' ? date("Y-m-d", $objPage->sitemapLastmodDate) : date("Y-m-d", time());
								$arrArticles['changefreq'] = $objPage->sitemapChangefreq;
								$arrArticles['priority'] = $objPage->sitemapPriority != 0 ?  '0.' . $objPage->sitemapPriority : '1.0';

								$arrPage['articles'][] = $arrArticles;

								$objContentElements = \ContentModel::findBy(['pid=?', 'ptable=?', 'addToSitemap=?', 'invisible!=?', '((type="image" OR type="download" OR type="downloads" OR type="gallery") OR (type="text" AND addImage="1"))'], [$objArticles->id, 'tl_article', '1', '1']);

								if ($objContentElements != null) {
									while ($objContentElements->next()) {
										if ($objContentElements->type == 'downloads' || $objContentElements->type == 'gallery') {
											if (isset($objContentElements->multiSRC)) {
												$objCeFiles = \FilesModel::findMultipleByUuid(deserialize($objContentElements->multiSRC));

												if ($objCeFiles != null) {
													while ($objCeFiles->next()) {
														$arrCe = [];

														$arrCe['ceId'] = $objPage->id . '.' . $objArticles->id . '.' . $objContentElements->id . '.' . $objCeFiles->id;
														$arrCe['ceType'] = $objContentElements->type == 'gallery' ? 'image' : $objContentElements->type;
														$arrCe['ceUrl'] = \Environment::get('base') . $objCeFiles->path;

														if ($objCeFiles->meta != null) {
															$arrMeta = deserialize($objCeFiles->meta);

															if (isset($arrMeta[$rootLanguage])) {
																$arrCe['ceTitle'] = $arrMeta[$rootLanguage]['title'];
																$arrCe['ceCaption'] = $arrMeta[$rootLanguage]['caption'];
															}
														}

														$arrPage['ce'][] = $arrCe;
													}
												}
											}
										} else {
											$arrCe = [];

											$objCeFile = \FilesModel::findByUuid($objContentElements->singleSRC);

											$arrCe['ceId'] = $objContentElements->id;
											$arrCe['ceType'] = $objContentElements->type == 'text' ? 'image' : $objContentElements->type;
											$arrCe['ceUrl'] = \Environment::get('base') . $objCeFile->path;

											if ($objCeFile->meta != null) {
												$arrMeta = deserialize($objCeFile->meta);

												if (isset($arrMeta[$rootLanguage])) {
													$arrCe['ceTitle'] = $arrMeta[$rootLanguage]['title'];
													$arrCe['ceCaption'] = $arrMeta[$rootLanguage]['caption'];
												}
											} else {
												if (isset($objContentElements->title)) {
													$arrCe['ceTitle'] = $objContentElements->title;
												}

												if (isset($objContentElements->caption)) {
													$arrCe['ceCaption'] = $objContentElements->caption;
												}
											}

											$arrPage['ce'][] = $arrCe;
										}
									}
								}
							}
						}

						$arrPages[$strPageUrl] = $arrPage;
					}
				}
			}
			// Get subpages
			if ((!$objPage->protected || \Config::get('indexProtected')) && ($arrSubpages = static::findSearchablePagesWithOptions($objPage->id, $blnIsSitemap, $rootLanguage)) != false) {
				$arrPages = array_merge($arrPages, $arrSubpages);
			}
		}

		return $arrPages;
	}

	/**
	 * Ping google and bing
	 *
	 * @param int $rootId
	 */
	public function pingGoogleBing($rootId, $inSitemapIndex = false)
	{
		$objRoot = \PageModel::findBy(['id=?'], [$rootId]);

		if ($objRoot != null && $objRoot->type != 'regular') {
			if (\Config::get('sitemapPingGoogleBing') && $objRoot->sitemapLastPing < (time() - 3600000) && $objRoot->inSitemapIndex == false) {
				$objPing = new \Request();

				//$pingGoogle = $objPing->send('http://www.google.com/ping?sitemap=' . $sitemapName . '.xml');
				//$pingBing = $objPing->send('http://www.bing.com/ping?sitemap=' . $sitemapName . '.xml');

				unset($objPing);

				$objRoot->sitemapLastPing = time();
				$objRoot->save();
			}
		}
	}


	/**
	 * Automatically update the lastmod for the page from page, articles or content elements
	 *
	 * @param DataContainer $dc
	 */
	public function updateSitemapLastmod(\Contao\DataContainer $dc)
	{
		$time = time();

		$objDatabase = \Database::getInstance();

		if ($dc->table == 'tl_content') {
			$objContentElement = $objDatabase->prepare("SELECT * FROM tl_content WHERE id=?")->limit(1)->execute($dc->id);
		}

		if ($dc->table != 'tl_page') {
			$objArticle = $objDatabase->prepare("SELECT * FROM tl_article WHERE id=?")->limit(1)->execute($objContentElement != null ? $objContentElement->pid : $dc->id);

			$objPage = $objDatabase->prepare("SELECT * FROM tl_page WHERE id=?")->limit(1)->execute($objArticle->pid);
		}

		$objLastmodDate = $objDatabase->prepare('UPDATE tl_page SET sitemapLastmodDate=? WHERE id=?')->execute($time, $objPage != null ? $objPage->id : $dc->id);
	}


	/**
	 * Build the sitemap index file
	 *
	 * @param int $rootId
	 */
	public function buildSitemapIndex()
	{
		$objDatabase = \Database::getInstance();

		$objSitemaps = $objDatabase->prepare("SELECT * FROM tl_page WHERE inSitemapIndex =? AND sitemapName !=?")->execute('1', '');

		if ($objSitemaps != null) {
			$objFile = new \File('web/share/' . \Config::get('sitemapIndexName') . '.xml', true);
			$objFile->truncate();
			$objFile->append('<?xml version="1.0" encoding="UTF-8"?>');
			$objFile->append('<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">');

			while ($objSitemaps->next()) {
				$objFile->append('  <sitemap>');
				$objFile->append('    <loc>' . \Environment::get('base') . 'share/' . $objSitemaps->sitemapName . '.xml</loc>');
				$objFile->append('    <lastmod>' . date('Y-m-d', time()) . '</lastmod>');
				$objFile->append('  </sitemap>');
			}

			$objFile->append('</sitemapindex>');
			$objFile->close();
		}
	}
}
