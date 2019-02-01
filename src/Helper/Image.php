<?php

namespace Anker\ModulesBundle\Helper;

class Image
{
	public static function make($image, $params = [])
	{
		$baseImage = TL_ROOT . '/web/' . trim($image, '/');

		if (substr(ltrim($image, '/'), 0, 8) == 'storage/' && !is_file($baseImage)) {
			mkdir(dirname($baseImage), 0755, true);
			file_put_contents($baseImage, file_get_contents('https://connect.5-anker.com/'.trim($image, '/')));
		}

		if (!is_file($baseImage)) {
			return new BinaryFileResponse(TL_ROOT. '/files/br24de/images/dummy.png');
		}

		$objFile = (object)pathinfo($image);
		$allParams = array_merge(['markpos' => 'bottom-left', 'mark' => 'watermark.png', 'markh' => '6w', 'markx' => '2w', 'marky' => '2w', 'fit' => 'max'], $params);
		ksort($allParams);

		$strCacheName = 'assets/images/' . substr($objFile->filename, -1) . '/' . $objFile->filename . '-' . substr(md5(http_build_query($allParams).filemtime($baseImage)), 0, 12) . '.' . $objFile->extension;

		if (!is_file(TL_ROOT . '/' .$strCacheName)) {
			return 'img/' . trim($image, '/') . '?' . http_build_query($params);
		}

		return $strCacheName;
	}
}
