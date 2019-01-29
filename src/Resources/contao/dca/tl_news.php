<?php

// Add palettes to tl_news

$GLOBALS['TL_DCA']['tl_news']['palettes']['default'] = str_replace(',cssClass', ',cssClass,offersQuery', $GLOBALS['TL_DCA']['tl_news']['palettes']['default']);

$GLOBALS['TL_DCA']['tl_news']['fields']['offersQuery'] = [
	'label'                   => &$GLOBALS['TL_LANG']['tl_news']['offersQuery'],
	'exclude'                 => false,
	'search'                  => false,
	'sorting'                 => false,
	'flag'                    => 1,
	'inputType'               => 'text',
	'eval'                    => ['mandatory'=>false, 'maxlength'=>255, 'tl_class'=>'w50'],
	'sql'                     => "varchar(255) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_news']['palettes']['default'] = str_replace(',author;', ',author;{meta_legend},pageTitle,description;', $GLOBALS['TL_DCA']['tl_news']['palettes']['default']);

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
