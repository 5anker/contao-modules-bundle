<?php

namespace Anker\ModulesBundle\Helper;

class Image
{
	public static function make($image, $params = [])
	{
		$baseImage = TL_ROOT . '/web/' . trim($image, '/');

		if (!is_file($baseImage)) {
			return '/files/br24de/images/dummy.png';
		}

		$objFile = (object)pathinfo($image);
		$allParams = array_merge(['markpos' => 'bottom-left', 'mark' => 'watermark.png', 'markh' => '6w', 'markx' => '2w', 'marky' => '2w'], $params);
		ksort($allParams);

		$strCacheName = 'assets/images/' . substr($objFile->filename, -1) . '/' . $objFile->filename . '-' . substr(md5(http_build_query($allParams)), 0, 12) . '.' . $objFile->extension;

		if (!is_file(TL_ROOT . '/' .$strCacheName)) {
			return 'img/' . trim($image, '/') . '?' . http_build_query($params);
		}

		return $strCacheName;
	}
}
