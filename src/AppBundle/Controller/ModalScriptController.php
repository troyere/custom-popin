<?php

namespace AppBundle\Controller;

use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ModalScriptController extends Controller
{

    /**
     * Create script file
     *
     * @Route("/modal-script/create", name="modal_script_create")
     * @Method({"GET"})
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function createFileAction(Request $request)
    {
        $response = new JsonResponse;
        try {
            $this->get('modal_script_service')->createFile();
        } catch (Exception $e) {
            $response->setData(array('error' => $e->getMessage()));
        }
        return $response;
    }

    /**
     * Download script file
     *
     * @Route("/modal-script/download", name="modal_script_download")
     * @Method({"GET"})
     *
     * @param Request $request
     * @return BinaryFileResponse
     * @throws Exception
     */
    public function openFileAction(Request $request)
    {
        $response = new BinaryFileResponse($this->get('modal_script_service')->getPath());
        return $response;
    }

}
