<?php

namespace Anker\ModulesBundle\Helper;

use Contao\File;
use ImageOptimizer;
use Spatie\Glide\GlideImage;

class Image
{
	public static function make($image, $params = [])
	{
		if (!is_file(TL_ROOT . '/web' . $image)) {
			return $image;
		}

		$objFile = new File($image);
		$strCacheName = 'assets/images/' . substr($objFile->filename, -1) . '/' . $objFile->filename . '-' . substr(md5($watermark . '-' . $position . '-' . $objFile->mtime), 0, 8) . '.' . $objFile->extension;

		if (is_file(TL_ROOT . '/' . $strCacheName)) {
			return $strCacheName;
		}

		$params = array_merge(['markpos' => 'bottom-left', 'mark' => '/files/br24de/images/watermark.png', 'markh' => '6w', 'markx' => '2w', 'marky' => '2w'], $params);

		GlideImage::create($image)
			->modify($params)
			->save($strCacheName);

		ImageOptimizer::optimize($strCacheName);

		return $strCacheName;
	}
}
