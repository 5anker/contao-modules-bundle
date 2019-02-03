<?php

namespace Anker\ModulesBundle\Classes;

use Hofff\Contao\LanguageRelations\LanguageRelations;

/**
 * Class extendedSitemapLanguageRelations
 */
class ExtendedSitemapLanguageRelations extends \Backend
{
	/**
	 * Get the language relations for the given page
	 *
	 * @param integer $id
	 *
	 * @return array
	 */
	public function findPageLanguageRelations($id)
	{
		$arrReturn = [];

		$arrLanguageRelations = LanguageRelations::getPagesRelatedTo($id);

		if ($arrLanguageRelations != null) {
			foreach ($arrLanguageRelations as $relatedPageId) {
				$objRelatedPage = \PageModel::findWithDetails($relatedPageId);

				if ($objRelatedPage != null) {
					$arrReturn[] = ['language' => $objRelatedPage->rootLanguage, 'url' => $objRelatedPage->getAbsoluteUrl()];
				}
			}
		}

		return $arrReturn;
	}
}
