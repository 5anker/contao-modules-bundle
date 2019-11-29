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
class BoatController extends Controller
{
    /**
     * Renders the content.
     *
     * @return Response
     *
     */
    public function boatAction(Request $request, $id)
    {
        $page = \PageModel::findOneBy('import_id', 'boat:' . $id);

        if ($page) {
            return new RedirectResponse('/' . $page->alias . '?' . http_build_query($request->query->all()), 301);
        }

        return new RedirectResponse('/', 301);
    }

    public function adacAction(Request $request, $id)
    {
        $page = \PageModel::findOneBy('import_id', 'boat:' . $id);

        if ($page) {
            return new RedirectResponse('/' . $page->alias . '?' . http_build_query($request->query->all()), 301);
        }

        return new RedirectResponse('https://www.chartercheck.com/yachtinfo.html?id=' . $id . '&' . http_build_query($request->query->all()), 301);
    }
}
