<?php

namespace Anker\ModulesBundle\Controller;

use Anker\ModulesBundle\Helper\Image;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Handles front end routes.
 *
 */
class ImageController extends Controller
{
	/**
	 * Renders the content.
	 *
	 * @return Response
	 *
	 */
	public function imgAction(Request $request, $path)
	{
		$webP = strpos($path, '.webp') !== false ? '.webp' : '';
		$path = str_replace('.webp', '', $path);

		$image = TL_ROOT . '/web/' . trim($path, '/');
		$split = explode('?', $request->getRequestUri());
		$query = count($split) == 2 ? $split[1] : '';

		parse_str($query, $params);

		if (substr(ltrim($path, '/'), 0, 8) == 'storage/' && !is_file($image)) {
			mkdir(dirname($image), 0755, true);
			file_put_contents($image, file_get_contents('https://connect.5-anker.com/'.trim($path, '/')));
		}

		if (!is_file($image)) {
			return new BinaryFileResponse(TL_ROOT. '/files/br24de/images/dummy.png');
		}

		$objFile = (object)pathinfo($image);
		$allParams = array_merge(['markpos' => 'bottom-left', 'mark' => 'watermark.png', 'markh' => '6w', 'markx' => '2w', 'marky' => '2w', 'fit' => 'max'], $params);
		ksort($allParams);

		mkdir(TL_ROOT . '/assets/images/' . substr($objFile->filename, -1), 0755, true);

		$strCacheName = 'assets/images/' . substr($objFile->filename, -1) . '/' . $objFile->filename . '-' . substr(md5(http_build_query($allParams).filemtime($image)), 0, 12) . '.' . $objFile->extension;

		if (!is_file(TL_ROOT . '/' .$strCacheName)) {
			$server = \League\Glide\ServerFactory::create([
				'watermarks' => TL_ROOT . '/files/br24de/images/',
				'source' => '/',
				'cache' => 'assets/images/glide',
				'group_cache_in_folders' => false
			]);

			$cachedImg = $server->makeImage($image, $allParams);

			rename(TL_ROOT. '/assets/images/glide/' . $cachedImg, TL_ROOT. '/' . $strCacheName);

			$optimizerChain = OptimizerChainFactory::create();
			$optimizerChain->optimize(TL_ROOT. '/' . $strCacheName);
		}

		if ($webP && !is_file(TL_ROOT. '/' . $strCacheName . $webP)) {
			Image::createWebPFile(TL_ROOT. '/' . $strCacheName, strtolower($objFile->extension));
		}

		$response = new Response();
		$fileStream = TL_ROOT. '/' . $strCacheName . $webP;

		if (! is_file($fileStream)) {
			$response->setStatusCode(404);

			return $response;
		}

		// Caching...
		$sLastModified = filemtime($fileStream);
		$sEtag = md5_file($fileStream);

		$sFileSize = filesize($fileStream);
		$aInfo = getimagesize($fileStream);

		if (in_array($sEtag, $request->getETags()) || $request->headers->get('If-Modified-Since') === gmdate("D, d M Y H:i:s", $sLastModified)." GMT") {
			$response->headers->set("Content-Type", $aInfo['mime']);
			$response->headers->set("Last-Modified", gmdate("D, d M Y H:i:s", $sLastModified)." GMT");
			$response->setETag($sEtag);
			$response->setPublic();
			$response->setStatusCode(304);

			return $response;
		}

		$oStreamResponse = new StreamedResponse();
		$oStreamResponse->headers->set("Content-Type", $aInfo['mime']);
		$oStreamResponse->headers->set("Content-Length", $sFileSize);
		$oStreamResponse->headers->set("ETag", $sEtag);
		$oStreamResponse->headers->set("Last-Modified", gmdate("D, d M Y H:i:s", $sLastModified)." GMT");

		$oStreamResponse->setCallback(function () use ($fileStream) {
			readfile($fileStream);
		});

		return $oStreamResponse;
	}
}
