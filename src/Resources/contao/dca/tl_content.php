<?php

// Add palettes to tl_content

$GLOBALS['TL_DCA']['tl_content']['palettes']['teaser_panels'] = '{type_legend},type;{nav_legend},pages;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests;{invisible_legend:hide},invisible,start,stop';

$GLOBALS['TL_DCA']['tl_content']['fields']['pages'] = [
	'label' => &$GLOBALS['TL_LANG']['tl_content']['pages'],
	'exclude' => true,
	'inputType' => 'pageTree',
	'foreignKey' => 'tl_page.title',
	'eval' => [
		'multiple' => true,
		'fieldType' => 'checkbox',
		'orderField' => 'orderPages',
		'mandatory'=>true
	],
	'load_callback' => [
		['tl_anker_content', 'setPagesFlags']
	],
	'sql'                     => "blob NULL",
	'relation'                => ['type'=>'hasMany', 'load'=>'lazy']
];

$GLOBALS['TL_DCA']['tl_content']['fields']['orderPages'] = [
	'label'                   => &$GLOBALS['TL_LANG']['MSC']['sortOrder'],
	'sql'                     => "blob NULL"
];

class tl_anker_content
{
	/**
	 * Dynamically change attributes of the "pages" field
	 *
	 * @param mixed         $varValue
	 * @param DataContainer $dc
	 *
	 * @return mixed
	 */
	public function setPagesFlags($varValue, DataContainer $dc)
	{
		if ($dc->activeRecord && $dc->activeRecord->type == 'search') {
			$GLOBALS['TL_DCA'][$dc->table]['fields'][$dc->field]['eval']['mandatory'] = false;
			unset($GLOBALS['TL_DCA'][$dc->table]['fields'][$dc->field]['eval']['orderField']);
		}

		return $varValue;
	}
}
