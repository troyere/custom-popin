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
     * @Route("/configs", name="save_config")
     * @Method({"POST"})
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function saveConfigAction(Request $request)
    {
        $response = new JsonResponse;
        try {
            $config = $request->get('config');
            if (null === $config) {
                throw new Exception('No config to save.');
            }
            $this->get('config_service')->save($config);
        } catch (Exception $e) {
            $response->setData(array('error' => $e->getMessage()));
        }
        return $response;
    }

    /**
     * Get config
     *
     * @Route("/configs", name="get_config")
     * @Method({"GET"})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getConfigAction(Request $request)
    {
        $response = new JsonResponse;
        try {
            $config = $this->get('config_service')->get();
            $response->setData(array('config' => $config));
        } catch (Exception $e) {
            $response->setData(array('error' => $e->getMessage()));
        }
        return $response;
    }

}
