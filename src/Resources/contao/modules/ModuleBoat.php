<?php

namespace Anker\ModulesBundle;

use Contao\Frontend;

class ModuleBoat extends Frontend
{

	/**
	 * Add FAQs to the indexer
	 *
	 * @param array   $arrPages
	 * @param integer $intRoot
	 * @param boolean $blnIsSitemap
	 *
	 * @return array
	 */
	public function getSearchablePages($arrPages, $intRoot = 0, $blnIsSitemap = false)
	{
		$arrRoot = [];
		$arrPages = [];

		if ($intRoot > 0) {
			$arrRoot = $this->Database->getChildRecords($intRoot, 'tl_page');
		}

		$time = \Date::floorToMinute();


		// Get all categories
		$objBoat = BoatModel::findAll();

		if ($objBoat !== null) {
			$objParent = \PageModel::findById(145);

			if ($objParent === null) {
				return [];
			}

			while ($objBoat->next()) {
				// The target page has not been published (see #5520)
				if (!$objParent->published || ($objParent->start != '' && $objParent->start > $time) || ($objParent->stop != '' && $objParent->stop <= ($time + 60))) {
					continue;
				}

				if ($blnIsSitemap) {
					// The target page is protected (see #8416)
					if ($objParent->protected) {
						continue;
					}

					// The target page is exempt from the sitemap (see #6418)
					if ($objParent->sitemap == 'map_never') {
						continue;
					}
				}

				// Generate the URL
				$strUrl = $objParent->getAbsoluteUrl(\Config::get('useAutoItem') ? '/%s' : '/items/%s');

				$arrPages[] = sprintf(preg_replace('/%(?!s)/', '%%', $strUrl), ($objBoat->alias ?: $objBoat->id));
			}
		}

		return $arrPages;
	}
}

class_alias(ModuleBoat::class, 'ModuleBoat');
