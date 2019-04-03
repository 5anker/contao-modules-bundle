<?php

namespace Anker\ModulesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Handles front end routes.
 *
 */
class RobotsController extends Controller
{
	/**
	 * Renders the content.
	 *
	 * @return Response
	 *
	 */
	public function robotsAction(Request $request, $path)
	{
		$page = \Database::getInstance()
			->prepare("SELECT * FROM tl_page WHERE published = 1 AND dns = ? LIMIT 1")
			->execute(\Environment::get('host'));

		$txt = file_get_contents(TL_ROOT.'/web/robots.txt.default');

		while ($page->next()) {
			$txt = $objFallbackRootPage->robotsTxtContent ?: $txt;

			if (!empty($page->sitemapName)) {
				$txt .= "\nSitemap: ". (\Environment::get('ssl') ? 'https://' : 'http://') . \Environment::get('host') . '/share/' . $page->sitemapName . '.xml';
			}
		}

		return new Response($txt, Response::HTTP_OK, ['content-type' => 'text/plain']);
	}
}
