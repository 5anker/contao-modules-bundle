<?php

$GLOBALS['TL_DCA']['tl_imports'] = [

	// Config
	'config' => [
		'dataContainer'               => 'Table',
		'sql' => [
			'keys' => [
				'id' => 'primary',
			]
		]
	],

	// Fields
	'fields' => [
		'id' => [
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		],
		'key' => [
			'sql'                     => "varchar(255) NOT NULL default ''"
		],
		'value' => [
			'sql'                     => "varchar(255) NOT NULL default ''"
		],
	]
];
