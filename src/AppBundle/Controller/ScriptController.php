<?php

namespace AppBundle\Controller;

use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ScriptController extends Controller
{

    /**
     * Show script file
     *
     * @Route("/script/show", name="script_show", methods = { "GET" })
     * @Template()
     *
     * @param Request $request
     * @return array
     */
    public function showAction(Request $request)
    {
        return array();
    }

}
