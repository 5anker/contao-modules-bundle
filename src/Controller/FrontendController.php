<?php

namespace Anker\ModulesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Handles front end routes.
 *
 */
class FrontendController extends Controller
{
	/**
	 * Renders the content.
	 *
	 * @return Response
	 *
	 */
	public function imgAction(Request $request, $path)
	{
		$image = TL_ROOT . '/web/' . trim($path, '/');
		$split = explode('?', $request->getRequestUri());
		$query = count($split) == 2 ? $split[1] : '';

		parse_str($query, $params);

		if (!is_file($image)) {
			return new BinaryFileResponse(TL_ROOT. '/files/br24de/images/dummy.png');
		}

		$objFile = (object)pathinfo($image);
		$allParams = array_merge(['markpos' => 'bottom-left', 'mark' => 'watermark.png', 'markh' => '6w', 'markx' => '2w', 'marky' => '2w', 'fit' => 'max'], $params);
		ksort($allParams);

		mkdir(TL_ROOT . '/assets/images/' . substr($objFile->filename, -1), 0755, true);

		$strCacheName = 'assets/images/' . substr($objFile->filename, -1) . '/' . $objFile->filename . '-' . substr(md5(http_build_query($allParams)), 0, 12) . '.' . $objFile->extension;

		if (!is_file(TL_ROOT . '/' .$strCacheName)) {
			$server = \League\Glide\ServerFactory::create([
				'watermarks' => TL_ROOT . '/files/br24de/images/',
				'source' => '/',
				'cache' => 'assets/images/glide',
				'group_cache_in_folders' => false
			]);

			$cachedImg = $server->makeImage($image, $allParams);

			rename(TL_ROOT. '/assets/images/glide/' . $cachedImg, TL_ROOT. '/' . $strCacheName);
		}

		return new BinaryFileResponse(TL_ROOT. '/' . $strCacheName);
	}
}
