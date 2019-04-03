<?php


$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] = str_replace('defaultChmod', 'defaultChmod;{gsitemap_legend:hide},sitemapPingGoogleBing', $GLOBALS['TL_DCA']['tl_settings']['palettes']['default']);

$GLOBALS['TL_DCA']['tl_settings']['fields']['sitemapPingGoogleBing'] = [
	'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['gsitemap_sitemapPingGoogleBing'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => ['tl_class'=>'w50 m12']
];
