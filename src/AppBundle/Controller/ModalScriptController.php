<?php

namespace AppBundle\Controller;

use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ModalScriptController extends Controller
{

    /**
     * Create script file
     *
     * @Route("/modal-script/create", name="modal_script_create")
     * @Method({"POST"})
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function createFileAction(Request $request)
    {
        $response = new JsonResponse;
        try {
            $request->get('modal_script_service')->create();
        } catch (Exception $e) {
            $response->setData(array('error' => $e->getMessage()));
        }
        return $response;
    }

}
