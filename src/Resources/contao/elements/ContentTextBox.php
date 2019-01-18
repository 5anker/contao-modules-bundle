<?php

namespace Anker\ModulesBundle;

class ContentTextBox extends \Contao\ContentElement
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_content_box';

	/**
	 * Generate the module
	 */
	protected function compile()
	{
		$this->text = \StringUtil::toHtml5($this->text);

		// Add the static files URL to images
		if ($staticUrl = \System::getContainer()->get('contao.assets.files_context')->getStaticUrl()) {
			$path = \Config::get('uploadPath') . '/';
			$this->text = str_replace(' src="' . $path, ' src="' . $staticUrl . $path, $this->text);
		}

		$this->Template->text = \StringUtil::encodeEmail($this->text);
		$this->Template->subheadline = $this->subheadline;
		$this->Template->addImage = false;

		// Add an image
		if ($this->addImage && $this->singleSRC != '') {
			$objModel = \FilesModel::findByUuid($this->singleSRC);

			if ($objModel !== null && is_file(TL_ROOT . '/' . $objModel->path)) {
				$this->singleSRC = $objModel->path;
				$this->addImageToTemplate($this->Template, $this->arrData, null, null, $objModel);
			}
		}
	}
}

class_alias(ContentTextBox::class, 'ContentTextBox');
