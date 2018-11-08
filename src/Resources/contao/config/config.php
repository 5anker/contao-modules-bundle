<?php

// Content elements
$GLOBALS['TL_CTE']['anker_satellite']['teaser_panels'] = '\Anker\ModulesBundle\ContentTeaserPanels';

// Style sheet
if (TL_MODE == 'BE') {
	$GLOBALS['TL_CSS'][] = 'bundles/ankermodules/modules.min.css|static';
}

// TL_HOOKS

$GLOBALS['TL_HOOKS']['postUpload'][] = ['Anker\ModulesBundle\Helper\Upload', 'processPostUpload'];
