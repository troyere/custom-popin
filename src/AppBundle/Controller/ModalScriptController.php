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
     * @Route("/modal-script/create", name="modal_script_create", methods = { "GET" })
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
            $response->setData(array('errors' => array($e->getMessage())));
        }
        return $response;
    }

    /**
     * Test if the file exists
     *
     * @Route("/modal-script/exists", name="modal_script_exists", methods = { "GET" })
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function fileExistsAction()
    {
        $response = new JsonResponse;
        try {
            $path = $this->get('modal_script_service')->getPath();
            $response->setData(array('file_exists' => is_file($path)));
        } catch (Exception $e) {
            $response->setData(array('errors' => array($e->getMessage())));
        }
        return $response;
    }

    /**
     * Download script file
     *
     * @Route("/modal-script/download", name="modal_script_download", methods = { "GET" })
     *
     * @param Request $request
     * @return BinaryFileResponse
     * @throws Exception
     */
    public function downloadFileAction(Request $request)
    {
        $path = $this->get('modal_script_service')->getPath();
        if (!is_file($path)) {
            throw new \Exception(sprintf('The file "%s" does not exists', $path));
        }
        return new BinaryFileResponse($path);
    }

}
