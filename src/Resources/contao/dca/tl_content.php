<?php

// Add palettes to tl_content

$GLOBALS['TL_DCA']['tl_content']['palettes']['teaser_panels'] = '{type_legend},type;{pages_legend},linkedPages;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests;{invisible_legend:hide},invisible,start,stop';

$GLOBALS['TL_DCA']['tl_content']['palettes']['header_panel'] = '{type_legend},type;{header_panel_legend},header_type,headline,subheadline,text;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests;{invisible_legend:hide},invisible,start,stop';

$GLOBALS['TL_DCA']['tl_content']['palettes']['text_image_box'] = '{type_legend},type;{header_panel_legend},headline,subheadline,text,addImage,linkedPages;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests;{invisible_legend:hide},invisible,start,stop';


$GLOBALS['TL_DCA']['tl_content']['fields']['header_type'] = [
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['header_type'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options' => [
		'mini',
		'normal',
		'more',
		'box',
	],
	'eval'                    => [
		'mandatory'=>true,
		'tl_class'=>'',
	],
	'reference'               => &$GLOBALS['TL_LANG']['tl_content'],
	'sql'                     => "varchar(255) NULL"
];

$GLOBALS['TL_DCA']['tl_content']['fields']['subheadline'] = [
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['subheadline'],
	'exclude'                 => true,
	'search'                  => true,
	'inputType'               => 'text',
	'eval'                    => ['maxlength'=>255, 'tl_class'=>'w50'],
	'sql'                     => "varchar(255) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_content']['fields']['linkedPages'] = [
	'label' => &$GLOBALS['TL_LANG']['tl_content']['linked_pages'],
	'exclude' => true,
	'inputType' => 'pageTree',
	'foreignKey' => 'tl_page.title',
	'eval' => [
		'multiple' => true,
		'fieldType' => 'checkbox',
		'orderField' => 'orderPages',
		'mandatory'=>false
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
