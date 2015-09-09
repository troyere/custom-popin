<?php

namespace AppBundle\Controller;

use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ConfigController extends Controller
{

    /**
     * Save config
     *
     * @Route("/config", name="save_config", methods = { "POST" })
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function saveAction(Request $request)
    {
        $response = new JsonResponse;
        try {
            $config = $request->get('config');
            if (null === $config) {
                throw new Exception('No config to save.');
            }
            $errors = $this->get('config_validator_service')->validate($config);
            if (count($errors) > 0) {
                $response->setData(array('errors' => $errors));
                return $response;
            }
            $this->get('config_service')->save($config);
            $this->get('script_service')->createFile();
        } catch (Exception $e) {
            $response->setData(array('errors' => array($e->getMessage())));
        }
        return $response;
    }

    /**
     * Get config
     *
     * @Route("/config", name="get_config", methods = { "GET" })
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getAction(Request $request)
    {
        $response = new JsonResponse;
        try {
            $config = $this->get('config_service')->get();
            $response->setData(array('config' => $config));
        } catch (Exception $e) {
            $response->setData(array('errors' => array($e->getMessage())));
        }
        return $response;
    }

    /**
     * Clear config
     *
     * @Route("/config", name="delete_config", methods = { "DELETE" })
     * @Route("/config/delete", methods = { "GET" })
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function deleteAction(Request $request)
    {
        $response = new JsonResponse;
        try {
            $this->get('config_service')->clear();
        } catch (Exception $e) {
            $response->setData(array('errors' => array($e->getMessage())));
        }
        return $response;
    }

}
