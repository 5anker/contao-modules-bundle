<?php

namespace Anker\ModulesBundle\Classes;

class Article
{
	public function parseArticles($objTemplate, $arrRow, $objModule)
	{
		// $objTemplate->count == 0 to check with its a single article

		if (isset($arrRow['news_template']) && !empty($arrRow['news_template']) && $objTemplate->count == 0) {
			$objTemplate->setName($arrRow['news_template']);
		}
	}
}
