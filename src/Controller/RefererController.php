<?php

namespace Anker\ModulesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Handles front end routes.
 *
 */
class RefererController extends Controller
{
	/**
	 * Renders the content.
	 *
	 * @return Response
	 *
	 */
	public function redirectAction(Request $request, $path)
	{
		setcookie('referer', $path, time()+60*60*24*7, "", "", false, false);

		return new RedirectResponse('/', 302);
	}
}
