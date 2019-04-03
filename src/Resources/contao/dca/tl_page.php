<?php

// Add palettes to tl_page

$GLOBALS['TL_DCA']['tl_page']['palettes']['root'] = str_replace('createSitemap;', 'createSitemap;{robots_legend:hide},robotsTxtContent;', $GLOBALS['TL_DCA']['tl_page']['palettes']['root']);
$GLOBALS['TL_DCA']['tl_page']['palettes']['regular'] = str_replace('protected;', 'protected;{image_legend:hide},backgroundSRC,teaserSRC,iconSRC;', $GLOBALS['TL_DCA']['tl_page']['palettes']['regular']);
$GLOBALS['TL_DCA']['tl_page']['palettes']['redirect'] = str_replace('protected;', 'protected;{image_legend:hide},backgroundSRC,teaserSRC,iconSRC;', $GLOBALS['TL_DCA']['tl_page']['palettes']['redirect']);
$GLOBALS['TL_DCA']['tl_page']['palettes']['forward'] = str_replace('protected;', 'protected;{image_legend:hide},backgroundSRC,teaserSRC,iconSRC;', $GLOBALS['TL_DCA']['tl_page']['palettes']['forward']);

$GLOBALS['TL_DCA']['tl_page']['fields']['alias']['save_callback'][] = ['tl_page_anker', 'checkAlias'];

$GLOBALS['TL_DCA']['tl_page']['fields']['robotsTxtContent'] = [
	'label'                   => &$GLOBALS['TL_LANG']['tl_page']['robotsTxtContent'],
	'exclude'                 => true,
	'inputType'               => 'textarea',
	'eval'                    => ['style'=>' min-height:60px', 'tl_class'=>'clr'],
	'sql'                     => "text NULL"
];

$GLOBALS['TL_DCA']['tl_page']['fields']['backgroundSRC'] = [
	'label'                   => &$GLOBALS['TL_LANG']['tl_page']['backgroundSRC'],
	'exclude'                 => true,
	'inputType'               => 'fileTree',
	'eval'                    => [
		'filesOnly'=>true,
		'fieldType'=>'radio',
		'mandatory'=>false,
		'tl_class'=>'w50',
		'extensions' => \Config::get('validImageTypes')
	],
	'sql'                     => "binary(16) NULL"
];


$GLOBALS['TL_DCA']['tl_page']['fields']['teaserSRC'] = [
	'label'                   => &$GLOBALS['TL_LANG']['tl_page']['teaserSRC'],
	'exclude'                 => true,
	'inputType'               => 'fileTree',
	'eval'                    => [
		'filesOnly'=>true,
		'fieldType'=>'radio',
		'mandatory'=>false,
		'tl_class'=>'w50 clr',
		'extensions' => \Config::get('validImageTypes')
	],
	'sql'                     => "binary(16) NULL"
];


$GLOBALS['TL_DCA']['tl_page']['fields']['iconSRC'] = [
	'label'                   => &$GLOBALS['TL_LANG']['tl_page']['iconSRC'],
	'exclude'                 => true,
	'inputType'               => 'fileTree',
	'eval'                    => [
		'filesOnly'=>true,
		'fieldType'=>'radio',
		'mandatory'=>false,
		'tl_class'=>'w50 clr',
		'extensions' => \Config::get('validImageTypes')
	],
	'sql'                     => "binary(16) NULL"
];

$GLOBALS['TL_DCA']['tl_page']['fields']['import_id'] = [
	'sql'                     => "varchar(100) NULL"
];

$GLOBALS['TL_DCA']['tl_page']['fields']['import_data'] = [
	'sql'                     => "longtext NULL"
];

// Sitemap

$GLOBALS['TL_DCA']['tl_page']['subpalettes']['createSitemap'] = 'sitemapName,inSitemapIndex';

$GLOBALS['TL_DCA']['tl_page']['palettes']['regular'] = str_replace(',sitemap,', ',', $GLOBALS['TL_DCA']['tl_page']['palettes']['regular']);
$GLOBALS['TL_DCA']['tl_page']['palettes']['regular'] = str_replace(';{expert_legend', ';{sitemap_legend},sitemap,sitemapPriority,sitemapChangefreq,sitemapLastmodAutomaticOff;{expert_legend', $GLOBALS['TL_DCA']['tl_page']['palettes']['regular']);

$GLOBALS['TL_DCA']['tl_page']['palettes']['__selector__'][] = 'sitemapLastmodAutomaticOff';
$GLOBALS['TL_DCA']['tl_page']['subpalettes']['sitemapLastmodAutomaticOff'] = 'sitemapLastmodDate';

$GLOBALS['TL_DCA']['tl_page']['fields']['sitemap']['eval'] = array_replace($GLOBALS['TL_DCA']['tl_page']['fields']['sitemap']['eval'], ['tl_class' => 'w50 clr']);
$GLOBALS['TL_DCA']['tl_page']['fields']['hide']['eval'] = array_replace($GLOBALS['TL_DCA']['tl_page']['fields']['cssClass']['eval'], ['tl_class' => 'w50 clr']);

$GLOBALS['TL_DCA']['tl_page']['fields']['inSitemapIndex'] = [
	'label'                   => &$GLOBALS['TL_LANG']['tl_page']['gsitemap_inSitemapIndex'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => ['tl_class'=>'w50 m12'],
	'sql'                     => "char(1) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_page']['fields']['sitemapPriority'] = [
	'label'                   => &$GLOBALS['TL_LANG']['tl_page']['gsitemap_sitemapPriority'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'				  => ['1'=>'0.1','2'=>'0.2','3'=>'0.3','4'=>'0.4','5'=>'0.5','6'=>'0.6','7'=>'0.7','8'=>'0.8','9'=>'0.9', '0'=>'1.0'],
	'eval'                    => ['tl_class'=>'w50 clr wizard'],
	'sql'                     => "int(1) NOT NULL default '0'"
];

$GLOBALS['TL_DCA']['tl_page']['fields']['sitemapChangefreq'] = [
	'label'                   => &$GLOBALS['TL_LANG']['tl_page']['gsitemap_sitemapChangefreq'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'				  => ['always','hourly','daily','weekly','monthly','yearly','never'],
	'eval'                    => ['tl_class'=>'w50'],
	'sql'                     => "varchar(10) NOT NULL default 'monthly'"
];

$GLOBALS['TL_DCA']['tl_page']['fields']['sitemapLastmodAutomaticOff'] = [
	'label'                   => &$GLOBALS['TL_LANG']['tl_page']['gsitemap_sitemapLastmodAutomaticOff'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => ['tl_class'=>'w50 clr','submitOnChange'=>true],
	'sql'                     => "char(1) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_page']['fields']['sitemapLastmodDate'] = [
	'label'                   => &$GLOBALS['TL_LANG']['tl_page']['gsitemap_sitemapLastmodDate'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => ['rgxp'=>'date', 'datepicker'=>true, 'tl_class'=>'w50 wizard'],
	'sql'                     => "varchar(11) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_page']['fields']['sitemapLastPing'] = [
	'sql'                     => "int(10) unsigned NOT NULL default '0'"
];

$GLOBALS['TL_DCA']['tl_page']['config']['onload_callback'][5] = ['tl_page_anker', 'generateSitemap'];
$GLOBALS['TL_DCA']['tl_page']['config']['onsubmit_callback'][] = ['\Anker\ModulesBundle\Classes\Sitemap', 'updateSitemapLastmod'];
$GLOBALS['TL_DCA']['tl_page']['list']['operations']['toggle']['button_callback'] = ['tl_page_anker', 'toggleIcon'];


class tl_page_anker extends tl_page
{
	/**
	 * Check for modified pages and update the XML files if necessary
	 */
	public function generateSitemap()
	{
		/** @var Symfony\Component\HttpFoundation\Session\SessionInterface $objSession */
		$objSession = System::getContainer()->get('session');
		$session = $objSession->get('sitemap_updater');
		if (empty($session) || !\is_array($session)) {
			return;
		}

		$this->import('Anker\ModulesBundle\Classes\SitemapAutomator', 'Automator');

		foreach ($session as $id) {
			$this->Automator->generateSitemap($id);
		}
		$objSession->set('sitemap_updater', null);
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
		// check if there is a news article with the same alias
		if (($objNews = \NewsModel::findByAlias($varValue)) !== null) {
			// get the redirect page
			if (($objTarget = \PageModel::findWithDetails($objNews->getRelated('pid')->jumpTo)) !== null) {
				// get the page
				$objPage = \PageModel::findWithDetails($dc->id);
				// check if page is on the same domain and language
				if ($objPage->domain == $objTarget->domain && (!\Config::get('addLanguageToUrl') || $objPage->rootLanguage == $objTarget->rootLanguage)) {
					// append id
					$varValue.= '-' . $dc->id;
				}
			}
		}
		// return the alias
		return $varValue;
	}
}
