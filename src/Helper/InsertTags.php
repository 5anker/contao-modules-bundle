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

		$arrSplit = explode('::', $strTag);

		if ($arrSplit[0] != 'thepage' && $arrSplit[0] != 'cache_thepage') {
			return false;
		}

		if (!isset($arrSplit[1])) {
			return false;
		}

		$url = null;
		$arrSplit2 = explode('?', $arrSplit[1]);

		if ($arrSplit2[0] == 'background') {
			$url = Helper::imageUrl($page->backgroundSRC ?: '8ba259fd-18b0-11e9-b2e0-5254a2018744');
		} elseif ($arrSplit2[0] == 'teaser') {
			$url = Helper::imageUrl($page->teaserSRC);
		} elseif ($arrSplit2[0] == 'icon') {
			$url = Helper::imageUrl($page->iconSRC);
		}

		if ($arrSplit2[0] == 'beratung') {
			$tpl = new \FrontendTemplate('beratung');

			return $tpl->parse();
		}

		if (count($arrSplit2) == 2) {
			return static::replaceInsertTagsImage('img::' . $url . '?' . $arrSplit2[1]);
		}

		return $url;
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
				ksort($output);

				return Image::make($split[0], $output);
			}

			return $split[0];
		} else {
			return false;
		}
	}

	public static function replaceInsertTagsGet($strTag)
	{
		// Parameter abtrennen
		$arrSplit = explode('::', $strTag);

		if ($arrSplit[0] != 'get' && $arrSplit[0] != 'cache_get') {
			//nicht unser Insert-Tag
			return false;
		}

		// Parameter angegeben?
		if (isset($arrSplit[1])) {
			list($form, $except) = explode(':', $arrSplit[1]);
			if ($form == '_form') {
				$output = [];
				foreach ($_GET as $k => $v) {
					if (!empty($except)) {
						$ex = explode(',', $except);
						if (in_array($k, $ex)) {
							continue;
						}
					}
					$output[] = '<input type="hidden" name="'.$k.'" value="'.$v.'" />';
				}
				return implode('', $output);
			} else {
				return ($_GET[$arrSplit[1]] ?? false);
			}
		} else {
			return false;
		}
	}
}
