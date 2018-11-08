<?php

System::loadLanguageFile('tl_content');
System::loadLanguageFile('tl_page');

$GLOBALS['TL_DCA']['tl_boat'] = [

	// Config
	'config' => [
		'dataContainer'               => 'Table',
		'closed'                      => true,
		'sql' => [
			'keys' => [
				'id' => 'primary',
				'boat_id' => 'index'
			]
		]
	],

	// List
	'list' => [
		'sorting' => [
			'mode'                    => 2,
			'fields'                  => ['title DESC'],
			'flag'                    => 1,
			'panelLayout'             => 'filter;sort,search,limit'
		],
		'label' => [
			'fields'                  => ['boat_id', 'title', 'model', 'company'],
			'showColumns'             => true,
		],
		'global_operations' => [
			'all' => [
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
			]
		],
		'operations' => [
			'edit' => [
				'label'               => &$GLOBALS['TL_LANG']['tl_content']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.svg'
			],
			'delete' => [
				'label'               => &$GLOBALS['TL_LANG']['tl_content']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.svg',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			],
			'show' => [
				'label'               => &$GLOBALS['TL_LANG']['tl_content']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.svg'
			],
		]
	],

	// Palettes
	'palettes' => [
		'default'                     => '{title_legend},title,alias;{meta_legend},pageTitle,description'
	],

	// Fields
	'fields' => [
		'id' => [
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		],
		'boat_id' => [
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		],
		'tstamp' => [
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		],
		'last_update' => [
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		],
		'data' => [
			'sql'                     => "longtext NULL"
		],
		'company' => [
			'label'                   => &$GLOBALS['TL_LANG']['tl_boat']['company'],
			'exclude'                 => false,
			'search'                  => true,
			'sorting'                 => true,
			'filter'                  => true,
			'flag'                    => 1,
			'sql'                     => "varchar(255) NOT NULL default ''"
		],
		'model' => [
			'label'                   => &$GLOBALS['TL_LANG']['tl_boat']['model'],
			'exclude'                 => false,
			'search'                  => true,
			'filter'                  => true,
			'sorting'                 => true,
			'sql'                     => "varchar(255) NOT NULL default ''"
		],
		'title' => [
			'label'                   => &$GLOBALS['TL_LANG']['tl_boat']['title'],
			'exclude'                 => false,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => ['mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'long'],
			'sql'                     => "varchar(255) NOT NULL default ''"
		],
		'alias' => [
			'label'                   => &$GLOBALS['TL_LANG']['tl_boat']['alias'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => ['doNotCopy'=>true, 'unique'=>true, 'maxlength'=>128, 'tl_class'=>'w50'],
			'sql'                     => "varchar(128) BINARY NOT NULL default ''"
		],
		'pageTitle' => [
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['pageTitle'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => ['maxlength'=>255, 'decodeEntities'=>true, 'tl_class'=>'w50'],
			'sql'                     => "varchar(255) NOT NULL default ''"
		],
		'description' => [
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['description'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => ['style'=>'height:60px', 'decodeEntities'=>true, 'tl_class'=>'clr'],
			'sql'                     => "text NULL"
		],
	]
];
