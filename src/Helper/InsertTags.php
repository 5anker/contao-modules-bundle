<?php

namespace Anker\ModulesBundle\Helper;

class InsertTags extends \Frontend
{
	public static function replaceInsertTagsPage($strTag)
	{
		$page = $GLOBALS['objPage'];

		if (!$page) {
			return false;
		}

		// Parameter abtrennen
		$arrSplit = explode('::', $strTag);

		if ($arrSplit[0] != 'thepage' && $arrSplit[0] != 'cache_thepage') {
			//nicht unser Insert-Tag
			return false;
		}

		// Parameter angegeben?
		if (isset($arrSplit[1]) && $arrSplit[1] == 'background') {
			return Helper::imageUrl($page->backgroundSRC ?: '8ba259fd-18b0-11e9-b2e0-5254a2018744');
		} elseif (isset($arrSplit[1]) && $arrSplit[1] == 'teaser') {
			return Helper::imageUrl($page->teaserSRC);
		} elseif (isset($arrSplit[1]) && $arrSplit[1] == 'icon') {
			return Helper::imageUrl($page->iconSRC);
		} elseif (isset($arrSplit[1]) && $arrSplit[1] == 'beratung') {
			$tpl = new \FrontendTemplate('beratung');

			return $tpl->parse();
		} else {
			return false;
		}
	}

	public static function replaceInsertTagsImage($strTag)
	{
		// Parameter abtrennen
		$arrSplit = explode('::', $strTag);

		if ($arrSplit[0] != 'img' && $arrSplit[0] != 'cache_img') {
			//nicht unser Insert-Tag
			return false;
		}

		// Parameter angegeben?
		if (isset($arrSplit[1])) {
			$query = $arrSplit[1];
			$split = explode('?', $query);

			if (count($split) == 2) {
				parse_str($split[1], $output);

				return Image::make($split[0], $output);
			}

			return $split[0];
		} else {
			return false;
		}
	}
}
