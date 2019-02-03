<?php

$GLOBALS['TL_CRON']['daily']['generateSitemap'] = ['Anker\ModulesBundle\Classes\SitemapAutomator', 'generateSitemap'];
$GLOBALS['TL_PURGE']['custom']['xml']['callback'] = ['Anker\ModulesBundle\Classes\SitemapAutomator', 'generateXmlFiles'];

$GLOBALS['TL_HOOKS']['replaceInsertTags'][] = ['Anker\ModulesBundle\Helper\InsertTags', 'replaceInsertTagsPage'];
$GLOBALS['TL_HOOKS']['replaceInsertTags'][] = ['Anker\ModulesBundle\Helper\InsertTags', 'replaceInsertTagsImage'];
$GLOBALS['TL_HOOKS']['replaceInsertTags'][] = ['Anker\ModulesBundle\Helper\InsertTags', 'replaceInsertTagsGet'];
$GLOBALS['TL_HOOKS']['replaceInsertTags'][] = ['Anker\ModulesBundle\Helper\InsertTags', 'replaceInsertTagsToern'];
$GLOBALS['TL_HOOKS']['parseArticles'][] = ['Anker\ModulesBundle\Classes\Article', 'parseArticles'];

// Content elements
$GLOBALS['TL_CTE']['anker_satellite']['teaser_panels'] = '\Anker\ModulesBundle\ContentTeaserPanels';
$GLOBALS['TL_CTE']['anker_satellite']['header_panel'] = '\Anker\ModulesBundle\ContentHeaderPanel';
$GLOBALS['TL_CTE']['anker_satellite']['text_image_box'] = '\Anker\ModulesBundle\ContentTextBox';

// Style sheet
if (TL_MODE == 'BE') {
	$GLOBALS['TL_CSS'][] = 'bundles/ankermodules/modules.min.css|static';
}

// TL_HOOKS

$GLOBALS['TL_HOOKS']['postUpload'][] = ['Anker\ModulesBundle\Helper\Upload', 'processPostUpload'];

$GLOBALS['TL_CRON']['minutely'][] = ['Anker\ModulesBundle\Helper\Import', 'importBoats'];

$GLOBALS["TL_HOOKS"]['generatePage'][] = ['Anker\ModulesBundle\Classes\NewsMeta', 'onGeneratePage'];


// disable extension if auto item or alias is disabled
if (\Config::get('useAutoItem') && !\Config::get('disableAlias')) {
	// set hooks
	$GLOBALS['TL_HOOKS']['getPageIdFromUrl'][] = ['Anker\ModulesBundle\Classes\NewsUrls','getPageIdFromUrl'];
	$GLOBALS['TL_HOOKS']['generateFrontendUrl'][] = ['Anker\ModulesBundle\Classes\NewsUrls','generateFrontendUrl'];
	$GLOBALS['TL_HOOKS']['parseArticles'][] = ['Anker\ModulesBundle\Classes\NewsUrls','parseArticles'];
	$GLOBALS['TL_HOOKS']['getSearchablePages'][] = ['Anker\ModulesBundle\Classes\NewsUrls', 'getSearchablePages'];
	// settings
	$GLOBALS['TL_CONFIG']['simpleNewsUrlsRedirect'] = 301;
}
