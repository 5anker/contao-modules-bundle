<?php

// Add palettes to tl_news
System::loadLanguageFile('tl_module');
System::loadLanguageFile('tl_page');

$GLOBALS['TL_DCA']['tl_news']['palettes']['default'] = str_replace(',cssClass', ',cssClass,offersQuery,offersTitle', $GLOBALS['TL_DCA']['tl_news']['palettes']['default']);

$GLOBALS['TL_DCA']['tl_news']['fields']['alias']['save_callback'][] = ['tl_news_anker', 'checkAlias'];

$GLOBALS['TL_DCA']['tl_news']['fields']['offersQuery'] = [
	'label'                   => &$GLOBALS['TL_LANG']['tl_news']['offersQuery'],
	'exclude'                 => false,
	'search'                  => false,
	'sorting'                 => false,
	'flag'                    => 1,
	'inputType'               => 'text',
	'eval'                    => ['mandatory'=>false, 'maxlength'=>255, 'tl_class'=>'clr w50'],
	'sql'                     => "varchar(255) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_news']['fields']['offersTitle'] = [
	'label'                   => &$GLOBALS['TL_LANG']['tl_news']['offersTitle'],
	'exclude'                 => false,
	'search'                  => false,
	'sorting'                 => false,
	'flag'                    => 1,
	'inputType'               => 'text',
	'eval'                    => ['mandatory'=>false, 'maxlength'=>255, 'tl_class'=>'w50'],
	'sql'                     => "varchar(255) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_news']['palettes']['default'] = str_replace(',author;', ',author;{meta_legend},pageTitle,description;', $GLOBALS['TL_DCA']['tl_news']['palettes']['default']);
$GLOBALS['TL_DCA']['tl_news']['palettes']['default'] = str_replace('{publish_legend}', '{template_legend:hide},news_template,bodyClass;{publish_legend}', $GLOBALS['TL_DCA']['tl_news']['palettes']['default']);

$GLOBALS['TL_DCA']['tl_news']['fields']['pageTitle'] = [
	'label' => &$GLOBALS['TL_LANG']['tl_news']['pageTitle'],
	'exclude'                 => true,
	'search'                  => true,
	'inputType'               => 'text',
	'eval'                    => ['maxlength'=>255, 'decodeEntities'=>true, 'tl_class'=>''],
	'sql'                     => "varchar(255) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_news']['fields']['description'] = [
	'label' => &$GLOBALS['TL_LANG']['tl_news']['description'],
	'exclude'                 => true,
	'search'                  => true,
	'inputType'               => 'textarea',
	'eval'                    => ['style'=>'height:60px', 'decodeEntities'=>true, 'tl_class'=>'clr'],
	'sql'                     => "text NULL"
];

$GLOBALS['TL_DCA']['tl_news']['fields']['news_template'] = [
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['news_template'],
	'default'                 => '',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => ['tl_module_news', 'getNewsTemplates'],
	'eval'                    => ['includeBlankOption'=>true, 'tl_class'=>'w50'],
	'sql'                     => "varchar(64) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_news']['fields']['bodyClass'] = [
	'label'                   => &$GLOBALS['TL_LANG']['tl_news']['bodyClass'],
	'exclude'                 => true,
	'search'                  => true,
	'inputType'               => 'text',
	'eval'                    => ['maxlength'=>64, 'tl_class'=>'w50'],
	'sql'                     => "varchar(64) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_news']['config']['onload_callback'][1] = ['tl_news_anker', 'generateFeed'];
$GLOBALS['TL_DCA']['tl_news']['list']['operations']['toggle']['button_callback'] = ['tl_news_anker', 'toggleIcon'];

class tl_news_anker extends tl_news
{
	/**
	 * Check for modified news feeds and update the XML files if necessary
	 */
	public function generateFeed()
	{
		/** @var Symfony\Component\HttpFoundation\Session\SessionInterface $objSession */
		$objSession = Contao\System::getContainer()->get('session');
		$session = $objSession->get('news_feed_updater');

		if (empty($session) || !\is_array($session)) {
			return;
		}

		$this->import('Contao\News', 'News');

		foreach ($session as $id) {
			$this->News->generateFeedsByArchive($id);
		}

		$this->import('Anker\ModulesBundle\Classes\SitemapAutomator', 'Automator');

		$this->Automator->generateSitemap();
		$objSession->set('news_feed_updater', null);
	}

	/**
	 * Check the news alias for duplicates
	 *
	 * @param mixed         $varValue
	 * @param DataContainer $dc
	 *
	 * @return string
	 *
	 * @throws Exception
	 */
	public function checkAlias($varValue, DataContainer $dc)
	{
		// get the news archive first
		if (($objArchive = \NewsArchiveModel::findById($dc->activeRecord->pid)) !== null) {
			// get the redirect page
			if (($objTarget = \PageModel::findById($objArchive->jumpTo)) !== null) {
				// check if there is a page with the same alias
				if (($objPage = \PageModel::findByAlias($varValue)) !== null) {
					// load the details
					$objTarget->current()->loadDetails();
					$objPage->current()->loadDetails();
					// check if page is on the same domain and language
					if ($objPage->domain == $objTarget->domain && (!\Config::get('addLanguageToUrl') || $objPage->rootLanguage == $objTarget->rootLanguage)) {
						// append id
						$varValue.= '-' . $dc->id;
					}
				}
			}
		}
		// return the alias
		return $varValue;
	}
}
