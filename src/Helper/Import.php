<?php

namespace Anker\ModulesBundle\Helper;

use Contao\System;
use Anker\ModulesBundle\BoatModel;
use Anker\ModulesBundle\ImportsModel;

class Import extends System
{
	public function importBoats()
	{
		//return true;

		$page = $this->getOption('boats_page', 1);

		$curler = new Curler('023d2dc527bcf90d339a0c49bb39174a');
		$query = $curler->get('remote/wp/boat', [
			'limit' => 50,
			'updated_at' => 'gt:' . $this->getOption('boats_last', '2015-01-01 00:00:00'),
			'sort' => 'id desc',
			'page' => $page
		]);

		foreach ($query->data as $boat) {
			$this->importBoat($boat);
		}

		$this->setOption('boats_page', ++$page);

		if ($query->meta->current_page >= $query->meta->last_page) {
			$this->setOption('boats_last', date('Y-m-d H:i:s', time()));
			$this->setOption('boats_page', 1);
		}

		System::log('Imported Boats', __METHOD__, TL_FILES);
	}

	public function importBoat($boat)
	{
		if ($np = \PageModel::findOneBy('import_id', 'boat:' . $boat->id)) {
			$np->import_id = 'boat:' . $boat->id;
			$np->import_data = json_encode($boat);
			$np->title = $boat->model->manufacturer->name . ' ' . $boat->model->name . ' Nr. ' . $boat->id;
			$np->alias = $boat->slug;
			$np->pageTitle = $boat->title . ' - Bootsreisen24';
			$np->description = $boat->metas->meta_description ?? '';
			$np->save();
		} else {
			$np = new \PageModel();
			$np->import_data = json_encode($boat);
			$np->import_id = 'boat:' . $boat->id;
			$np->pid = 201;
			$np->tstamp = time();
			$np->sorting = 846;
			$np->sitemapPriority = 5;
			$np->title = $boat->model->manufacturer->name . ' ' . $boat->model->name . ' Nr. ' . $boat->id;
			$np->alias = $boat->slug;
			$np->description = $boat->metas->meta_description ?? '';
			$np->type = 'regular';
			$np->pageTitle = $boat->title . ' - Bootsreisen24';
			$np->robots = 'index,follow';
			$np->redirect = 'permanent';
			$np->chmod = 'a:9:{i:0;s:2:"u1";i:1;s:2:"u2";i:2;s:2:"u3";i:3;s:2:"u4";i:4;s:2:"u5";i:5;s:2:"u6";i:6;s:2:"g4";i:7;s:2:"g5";i:8;s:2:"g6";}';
			$np->sitemap = 'map_default';
			$np->hide = 1;
			$np->published = 1;
			$np->ogType = 'website';
			$np->canonicalType = 'self';
			$np->ogTags = 'a:5:{s:7:"ogTitle";s:0:"";s:13:"ogDescription";s:0:"";s:12:"ogDeterminer";s:0:"";s:8:"ogLocale";s:0:"";s:17:"ogLocaleAlternate";s:0:"";}';
			$np->save();

			$npa = new \ArticleModel();
			$npa->pid = $np->id;
			$npa->sorting = 64;
			$npa->tstamp = time();
			$npa->title = $boat->model->manufacturer->name . ' ' . $boat->model->name . ' Nr. ' . $boat->id;
			$npa->alias = $boat->slug;
			$npa->author = 1;
			$npa->inColumn = 'before';
			$npa->customTpl = 'mod_article_boat';
			$npa->published = 1;
			$npa->save();
		}

		if ($bo = BoatModel::findOneBy('boat_id', $boat->id)) {
		} else {
			$bo = new BoatModel();
			$bo->tstamp = time();
			$bo->boat_id = $boat->id;
		}

		$bo->data = json_encode($boat);
		$bo->last_update = time();
		$bo->company = $boat->team->company;
		$bo->model = $boat->model->manufacturer->name . ' ' . $boat->model->name;
		$bo->title = $boat->title;
		$bo->alias = $boat->slug;
		$bo->description = $boat->metas->meta_description ?? '';
		$bo->save();
	}

	//

	public function getOption($key, $default = null)
	{
		if ($i = ImportsModel::findOneBy('key', $key)) {
			return $i->value ?: $default;
		}

		$i = new ImportsModel();
		$i->key = $key;
		$i->value = $default;
		$i->save();

		return $default;
	}

	public function setOption($key, $val = null)
	{
		if (!$i = ImportsModel::findOneBy('key', $key)) {
			$i = new ImportsModel();
			$i->key = $key;
		}

		$i->value = $val;
		$i->save();

		return $i;
	}
}
