<?php

// Add palettes to tl_page

$GLOBALS['TL_DCA']['tl_page']['palettes']['regular'] = str_replace('description;', 'description;{image_legend:hide},backgroundSRC,teaserSRC;', $GLOBALS['TL_DCA']['tl_page']['palettes']['regular']);

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
