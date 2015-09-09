<?php

namespace AppBundle\Controller;

use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ScriptController extends Controller
{

    /**
     * Download script file
     *
     * @Route("/script/show", name="modal_script_show", methods = { "GET" })
     *
     * @param Request $request
     * @return BinaryFileResponse
     * @throws Exception
     */
    public function showAction(Request $request)
    {
        $path = $this->get('script_service')->getPath();
        if (!is_file($path)) {
            throw new Exception(sprintf('The file "%s" does not exists', $path));
        }
        return new BinaryFileResponse($path);
    }

}
