<?php

declare(strict_types=1);

namespace Anker\ModulesBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Anker\ModulesBundle\AnkerModulesBundle;
use Symfony\Component\HttpKernel\KernelInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\ManagerPlugin\Routing\RoutingPluginInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;

class Plugin implements BundlePluginInterface, RoutingPluginInterface
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

	/**
	 * {@inheritdoc}
	 */
	public function getRouteCollection(LoaderResolverInterface $resolver, KernelInterface $kernel)
	{
		return $resolver
			->resolve(__DIR__.'/../Resources/config/routing.yml')
			->load(__DIR__.'/../Resources/config/routing.yml')
		;
	}
}
