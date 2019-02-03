<?php

// Add palettes to tl_content

$GLOBALS['TL_DCA']['tl_content']['palettes']['teaser_panels'] = '{type_legend},type;{pages_legend},linkedPages;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests;{invisible_legend:hide},invisible,start,stop';

$GLOBALS['TL_DCA']['tl_content']['palettes']['header_panel'] = '{type_legend},type;{header_panel_legend},header_type,headline,subheadline,text,addImage;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests;{invisible_legend:hide},invisible,start,stop';

$GLOBALS['TL_DCA']['tl_content']['palettes']['text_image_box'] = '{type_legend},type;{header_panel_legend},headline,subheadline;text,addImage,listitems;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID;{invisible_legend:hide},invisible,start,stop';


$GLOBALS['TL_DCA']['tl_content']['fields']['header_type'] = [
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['header_type'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options' => [
		'mini',
		'normal',
		'more',
		'box',
	],
	'eval'                    => [
		'mandatory'=>true,
		'tl_class'=>'',
	],
	'reference'               => &$GLOBALS['TL_LANG']['tl_content'],
	'sql'                     => "varchar(255) NULL"
];

$GLOBALS['TL_DCA']['tl_content']['fields']['subheadline'] = [
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['subheadline'],
	'exclude'                 => true,
	'search'                  => true,
	'inputType'               => 'text',
	'eval'                    => ['maxlength'=>255, 'tl_class'=>'w50'],
	'sql'                     => "varchar(255) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_content']['fields']['linkedPages'] = [
	'label' => &$GLOBALS['TL_LANG']['tl_content']['linked_pages'],
	'exclude' => true,
	'inputType' => 'pageTree',
	'foreignKey' => 'tl_page.title',
	'eval' => [
		'multiple' => true,
		'fieldType' => 'checkbox',
		'orderField' => 'orderPages',
		'mandatory'=>false
	],
	'load_callback' => [
		['tl_anker_content', 'setPagesFlags']
	],
	'sql'                     => "blob NULL",
	'relation'                => ['type'=>'hasMany', 'load'=>'lazy']
];

$GLOBALS['TL_DCA']['tl_content']['fields']['orderPages'] = [
	'label'                   => &$GLOBALS['TL_LANG']['MSC']['sortOrder'],
	'sql'                     => "blob NULL"
];

// Sitemap


$GLOBALS['TL_DCA']['tl_content']['palettes']['image'] = str_replace('{expert_legend', '{gsitemap_legend},addToSitemap;{expert_legend', $GLOBALS['TL_DCA']['tl_content']['palettes']['image']);
$GLOBALS['TL_DCA']['tl_content']['palettes']['download'] = str_replace('guests', 'guests,addToSitemap', $GLOBALS['TL_DCA']['tl_content']['palettes']['download']);
$GLOBALS['TL_DCA']['tl_content']['palettes']['downloads'] = str_replace('guests', 'guests,addToSitemap', $GLOBALS['TL_DCA']['tl_content']['palettes']['downloads']);
$GLOBALS['TL_DCA']['tl_content']['palettes']['gallery'] = str_replace('guests', 'guests,addToSitemap', $GLOBALS['TL_DCA']['tl_content']['palettes']['gallery']);

$GLOBALS['TL_DCA']['tl_content']['subpalettes']['addImage'] = str_replace('floating', 'floating,addToSitemap', $GLOBALS['TL_DCA']['tl_content']['subpalettes']['addImage']);

$GLOBALS['TL_DCA']['tl_content']['fields']['addToSitemap'] = [
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['gsitemap_addToSitemap'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => ['tl_class'=>'w50'],
	'sql'                     => "char(1) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_content']['config']['onsubmit_callback'][] = ['\Anker\ModulesBundle\Classes\Sitemap', 'updateSitemapLastmod'];

if (Input::get('do') == 'news') {
	if (($search_key = array_search('tl_content_news', array_column($GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'], 0))) !== false) {
		$GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'][$search_key] = ['tl_anker_content', 'generateFeed'];
	}
}

class tl_anker_content
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
	 * Dynamically change attributes of the "pages" field
	 *
	 * @param mixed         $varValue
	 * @param DataContainer $dc
	 *
	 * @return mixed
	 */
	public function setPagesFlags($varValue, DataContainer $dc)
	{
		if ($dc->activeRecord && $dc->activeRecord->type == 'search') {
			$GLOBALS['TL_DCA'][$dc->table]['fields'][$dc->field]['eval']['mandatory'] = false;
			unset($GLOBALS['TL_DCA'][$dc->table]['fields'][$dc->field]['eval']['orderField']);
		}

		return $varValue;
	}
}
