<?php

declare(strict_types=1);

namespace Anker\ModulesBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Anker\ModulesBundle\AnkerModulesBundle;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;

class Plugin implements BundlePluginInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function getBundles(ParserInterface $parser): array
	{
		return [
			BundleConfig::create(AnkerModulesBundle::class)
				->setLoadAfter([ContaoCoreBundle::class])
				->setReplace(['modules']),
		];
	}
}
