<?php

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

namespace Anker\ModulesBundle;

use Contao\Module;
use Patchwork\Utf8;
use Contao\CoreBundle\Exception\PageNotFoundException;

/**
 * Class ModuleFaqReader
 *
 * @property Comments $Comments
 * @property string   $com_template
 * @property array    $faq_categories
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class ModuleBoatReader extends Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_boatreader';

	/**
	 * Display a wildcard in the back end
	 *
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE') {
			$objTemplate = new \BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### ' . Utf8::strtoupper($GLOBALS['TL_LANG']['FMD']['boatreader'][0]) . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		// Set the item from the auto_item parameter
		if (!isset($_GET['items']) && \Config::get('useAutoItem') && isset($_GET['auto_item'])) {
			\Input::setGet('items', \Input::get('auto_item'));
		}

		// Do not index or cache the page if no FAQ has been specified
		if (!\Input::get('items')) {
			/** @var PageModel $objPage */
			global $objPage;

			$objPage->noSearch = 1;
			$objPage->cache = 0;

			return '';
		}

		return parent::generate();
	}

	/**
	 * Generate the module
	 */
	protected function compile()
	{
		/** @var PageModel $objPage */
		global $objPage;

		$objBoat = BoatModel::findByIdOrAlias(\Input::get('items'));

		if (null === $objBoat) {
			throw new PageNotFoundException('Page not found: ' . \Environment::get('uri'));
		}

		// Overwrite the page title and description (see #2853 and #4955)
		if ($objBoat->pageTitle != '') {
			$objPage->pageTitle = strip_tags(\StringUtil::stripInsertTags($objBoat->pageTitle));
		}


		if ($objBoat->description != '') {
			$objPage->description = $this->prepareMetaDescription($objBoat->description);
		}

		$this->Template->boat_id = $objBoat->boat_id;
	}
}

class_alias(ModuleBoatReader::class, 'ModuleBoatReader');
