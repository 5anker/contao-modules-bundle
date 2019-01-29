<?php

namespace Anker\ModulesBundle\Classes;

use Contao\Frontend;
use Contao\NewsModel;
use Contao\PageModel;
use Contao\LayoutModel;
use Contao\PageRegular;

class NewsMeta extends Frontend
{
	public function onGeneratePage(PageModel $objPage, LayoutModel $objLayout, PageRegular $objPageRegular)
	{
		if (!$this->Input->get('items')) {
			return; // no news
		}

		$news = NewsModel::findOneBy(['alias=?', 'published=?'], [$this->Input->get('items'), 1]);
		if (null === $news) {
			return; // not found
		}

		if ($news->headline) {
			$objPage->title = $news->headline;
		}

		if ($news->pageTitle) {
			$objPage->pageTitle = $news->pageTitle;
		}

		if ($news->description) {
			$objPage->description = $news->description;
		}
	}
}
