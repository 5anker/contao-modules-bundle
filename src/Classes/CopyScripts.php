<?php

namespace Anker\ModulesBundle\Classes;

class CopyScripts
{
	/**
	 * Only root pages should have sitemap names
	 */
	public function copy()
	{
		// https://www.googletagmanager.com/gtag/js?id=GTM-NGL4S34

		// gtag
		$objFile = new \File('files/br24de/js/analytics.js', true);
		$objFile->truncate();
		$objFile->append(file_get_contents('https://www.google-analytics.com/analytics.js'));
		$objFile->close();

		$c = file_get_contents('https://www.googletagmanager.com/gtag/js?id=GTM-NGL4S34');
		$c = str_replace('google-analytics.com', 'bootsreisen24.de/files/br24de/js', $c);

		// gtag
		$objFile = new \File('files/br24de/js/gtag.js', true);
		$objFile->truncate();
		$objFile->append($c);
		$objFile->close();
	}
}
