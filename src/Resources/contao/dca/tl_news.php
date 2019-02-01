<?php

// Add palettes to tl_news
System::loadLanguageFile('tl_module');
System::loadLanguageFile('tl_page');

$GLOBALS['TL_DCA']['tl_news']['palettes']['default'] = str_replace(',cssClass', ',cssClass,offersQuery,offersTitle', $GLOBALS['TL_DCA']['tl_news']['palettes']['default']);

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
