<?php


$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] = str_replace('disableIpCheck;', 'disableIpCheck;{gsitemap_legend:hide},activateSitemapIndex;', $GLOBALS['TL_DCA']['tl_settings']['palettes']['default']);

$GLOBALS['TL_DCA']['tl_settings']['palettes']['__selector__'][] = 'activateSitemapIndex';
$GLOBALS['TL_DCA']['tl_settings']['subpalettes']['activateSitemapIndex'] = 'sitemapIndexName,sitemapPingGoogleBing';

$GLOBALS['TL_DCA']['tl_settings']['fields']['activateSitemapIndex'] = [
	'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['gsitemap_activateSitemapIndex'],
	'inputType'               => 'checkbox',
	'eval'                    => ['submitOnChange'=>true]
];

$GLOBALS['TL_DCA']['tl_settings']['fields']['sitemapIndexName'] = [
	'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['gsitemap_sitemapIndexName'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => ['mandatory'=>true, 'rgxp'=>'alnum', 'decodeEntities'=>true, 'maxlength'=>32, 'tl_class'=>'w50']
];

$GLOBALS['TL_DCA']['tl_settings']['fields']['sitemapPingGoogleBing'] = [
	'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['gsitemap_sitemapPingGoogleBing'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => ['tl_class'=>'w50 m12']
];
