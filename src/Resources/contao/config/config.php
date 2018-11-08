<?php

// Add back end modules
array_insert($GLOBALS['BE_MOD']['content'], 5, [
	'boats' => [
		'tables' => ['tl_boat']
	]
]);

// Front end modules
array_insert($GLOBALS['FE_MOD'], 3, [
	'anker_satellite' => [
		'boatreader' => 'ModuleBoatReader',
	]
]);

// Content elements
$GLOBALS['TL_CTE']['anker_satellite']['teaser_panels'] = '\Anker\ModulesBundle\ContentTeaserPanels';

// Style sheet
if (TL_MODE == 'BE') {
	$GLOBALS['TL_CSS'][] = 'bundles/ankermodules/modules.min.css|static';
}

// TL_HOOKS

$GLOBALS['TL_HOOKS']['getSearchablePages'][] = ['Anker\ModulesBundle\ModuleBoat', 'getSearchablePages'];
$GLOBALS['TL_HOOKS']['postUpload'][] = ['Anker\ModulesBundle\Helper\Upload', 'processPostUpload'];

$GLOBALS['TL_CRON']['minutely'][] = ['Anker\ModulesBundle\Helper\Import', 'importBoats'];
