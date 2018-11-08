<?php

namespace Anker\ModulesBundle;

use Contao\Model;

/**
 * Reads and writes Boats
 *
 * @property integer $id
 * @property integer $tstamp
 * @property string  $title
 * @property integer $boat_id
 * @property string $pageTitle
 * @property string $description
 * @property string $company
 * @property string $model
 *
 * @method static BoatModel|null findById($id, array $opt=array())
 * @method static BoatModel|null findByIdOrAlias($val, array $opt=array())
 * @method static BoatModel|null findOneBy($col, $val, array $opt=array())
 * @method static BoatModel|null findOneByTstamp($val, array $opt=array())
 * @method static BoatModel|null findOneByTitle($val, array $opt=array())
 *
 * @method static Model\Collection|BoatModel[]|BoatModel|null findByTstamp($val, array $opt=array())
 * @method static Model\Collection|BoatModel[]|BoatModel|null findByTitle($val, array $opt=array())
 * @method static Model\Collection|BoatModel[]|BoatModel|null findMultipleByIds($val, array $opt=array())
 * @method static Model\Collection|BoatModel[]|BoatModel|null findBy($col, $val, array $opt=array())
 * @method static Model\Collection|BoatModel[]|BoatModel|null findAll(array $opt=array())
 *
 * @method static integer countById($id, array $opt=array())
 * @method static integer countByTstamp($val, array $opt=array())
 * @method static integer countByTitle($val, array $opt=array())
 */
class BoatModel extends Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_boat';
}

class_alias(BoatModel::class, 'BoatModel');
