<?php

namespace Anker\ModulesBundle;

class ContentTeaserPanels extends \Contao\ContentElement
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_teaser_panels';

	public function generate()
	{
		if (TL_MODE == 'BE') {
		}

		// Always return an array (see #4616)
		$this->linkedPages = \StringUtil::deserialize($this->linkedPages, true);

		if (empty($this->linkedPages) || $this->linkedPages[0] == '') {
			return '';
		}

		$strBuffer = parent::generate();

		return ($this->Template->items != '') ? $strBuffer : '';
	}

	/**
	 * Generate the module
	 */
	protected function compile()
	{
		/** @var PageModel $objPage */
		global $objPage;

		$items = [];
		$groups = [];

		// Get all groups of the current front end user
		if (FE_USER_LOGGED_IN) {
			$this->import('FrontendUser', 'User');
			$groups = $this->User->groups;
		}

		// Get all active pages
		$objPages = \PageModel::findPublishedRegularWithoutGuestsByIds($this->linkedPages);

		// Return if there are no pages
		if ($objPages === null) {
			return;
		}

		$arrPages = [];

		// Sort the array keys according to the given order
		if ($this->orderPages != '') {
			$tmp = \StringUtil::deserialize($this->orderPages);

			if (!empty($tmp) && \is_array($tmp)) {
				$arrPages = array_map(function () {
				}, array_flip($tmp));
			}
		}

		// Add the items to the pre-sorted array
		while ($objPages->next()) {
			$arrPages[$objPages->id] = $objPages->current();
		}

		$arrPages = array_values(array_filter($arrPages));

		/** @var PageModel[] $arrPages */
		foreach ($arrPages as $objModel) {
			$_groups = \StringUtil::deserialize($objModel->groups);

			// Do not show protected pages unless a front end user is logged in
			if (!$objModel->protected || (\is_array($_groups) && \count(array_intersect($_groups, $groups))) || $this->showProtected) {
				// Get href
				switch ($objModel->type) {
					case 'redirect':
						$href = $objModel->url;
						break;

					case 'forward':
						if (($objNext = $objModel->getRelated('jumpTo')) instanceof PageModel || ($objNext = \PageModel::findFirstPublishedRegularByPid($objModel->id)) instanceof PageModel) {
							/** @var PageModel $objNext */
							$href = $objNext->getFrontendUrl();
							break;
						}
						// DO NOT ADD A break; STATEMENT

						// no break
					default:
						$href = $objModel->getFrontendUrl();
						break;
				}

				$trail = \in_array($objModel->id, $objPage->trail);

				// Active page
				if ($objPage->id == $objModel->id && $href == \Environment::get('request')) {
					$strClass = trim($objModel->cssClass);
					$row = $objModel->row();

					$row['isActive'] = true;
					$row['isTrail'] = false;
					$row['class'] = trim('active ' . $strClass);
					$row['title'] = \StringUtil::specialchars($objModel->title, true);
					$row['pageTitle'] = \StringUtil::specialchars($objModel->pageTitle, true);
					$row['link'] = $objModel->title;
					$row['href'] = $href;
					$row['nofollow'] = (strncmp($objModel->robots, 'noindex,nofollow', 16) === 0);
					$row['target'] = '';
					$row['description'] = str_replace(["\n", "\r"], [' ', ''], $objModel->description);
					$row['background'] = '';

					if ($objModel->teaserSRC != '') {
						$objFile = \FilesModel::findByUuid($objModel->teaserSRC);

						if (!($objFile === null || !is_file(TL_ROOT . '/' . $objFile->path))) {
							$row['background'] = $objFile->path;
						}
					}

					// Override the link target
					if ($objModel->type == 'redirect' && $objModel->target) {
						$row['target'] = ' target="_blank"';
					}

					$items[] = $row;
				} else { // Regular
					$strClass = trim($objModel->cssClass . ($trail ? ' trail' : ''));
					$row = $objModel->row();

					$row['isActive'] = false;
					$row['isTrail'] = $trail;
					$row['class'] = $strClass;
					$row['title'] = \StringUtil::specialchars($objModel->title, true);
					$row['pageTitle'] = \StringUtil::specialchars($objModel->pageTitle, true);
					$row['link'] = $objModel->title;
					$row['href'] = $href;
					$row['nofollow'] = (strncmp($objModel->robots, 'noindex,nofollow', 16) === 0);
					$row['target'] = '';
					$row['description'] = str_replace(["\n", "\r"], [' ', ''], $objModel->description);
					$row['background'] = '';

					if ($objModel->teaserSRC != '') {
						$objFile = \FilesModel::findByUuid($objModel->teaserSRC);

						if (!($objFile === null || !is_file(TL_ROOT . '/' . $objFile->path))) {
							$row['background'] = $objFile->path;
						}
					}

					// Override the link target
					if ($objModel->type == 'redirect' && $objModel->target) {
						$row['target'] = ' target="_blank"';
					}

					$items[] = $row;
				}
			}
		}

		// Add classes first and last
		$items[0]['class'] = trim($items[0]['class'] . ' first');
		$last = \count($items) - 1;
		$items[$last]['class'] = trim($items[$last]['class'] . ' last');

		$this->Template->items = !empty($items) ? $items : '';
	}
}

class_alias(ContentTeaserPanels::class, 'ContentTeaserPanels');
