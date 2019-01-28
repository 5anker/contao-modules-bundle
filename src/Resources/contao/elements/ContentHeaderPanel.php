<?php

namespace Anker\ModulesBundle;

class ContentHeaderPanel extends \Contao\ContentElement
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_header_panel';

	public function generate()
	{
		if (TL_MODE == 'BE') {
			$objTemplate = new \BackendTemplate('be_wildcard');
			$objTemplate->wildcard = $this->text;
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;

			return $objTemplate->parse();
		}

		if ($this->header_type == 'normal') {
			$this->strTemplate = 'ce_jumbotron';
		} elseif ($this->header_type == 'more') {
			$this->strTemplate = 'ce_jumbotron_more';
		} elseif ($this->header_type == 'box') {
			$this->strTemplate = 'ce_jumbobox';
		} elseif ($this->header_type == 'mini') {
			$this->strTemplate = 'ce_jumbomini';
		} elseif ($this->header_type == 'thumb') {
			$this->strTemplate = 'ce_jumbotron_thumb';
		}

		return parent::generate();
	}

	/**
	 * Generate the module
	 */
	protected function compile()
	{
		/** @var PageModel $objPage */
		global $objPage;

		$this->Template->cssClass = $objPage->cssClass;
		$this->Template->subheadline = $this->subheadline;
		$this->Template->text = \StringUtil::toHtml5($this->text);

		// Add the static files URL to images
		if ($staticUrl = \System::getContainer()->get('contao.assets.files_context')->getStaticUrl()) {
			$path = \Config::get('uploadPath') . '/';
			$this->Template->text = str_replace(' src="' . $path, ' src="' . $staticUrl . $path, $this->Template->text);
		}

		$this->Template->text = \StringUtil::encodeEmail($this->Template->text);

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

class_alias(ContentHeaderPanel::class, 'ContentHeaderPanel');
