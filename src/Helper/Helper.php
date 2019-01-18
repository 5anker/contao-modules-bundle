<?php

namespace Anker\ModulesBundle\Helper;

class Helper
{
	public static function imageUrl($image = null)
	{
		if (empty($image)) {
			return '';
		}

		$objFile = \FilesModel::findByUuid($image);

		if (!($objFile === null || !is_file(TL_ROOT . '/' . $objFile->path))) {
			return $objFile->path;
		}

		return '';
	}

	public static function currentPage()
	{
		return $GLOBALS['objPage'];
	}
}
