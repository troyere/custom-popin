<?php

namespace AppBundle\Services;

use AppBundle\Services\ConfigService;

class ModalScriptService
{

    /**
     * @var ConfigService
     */
    protected $config;

    public function __construct(ConfigService $config)
    {
        $this->config = $config;
    }

    public function create()
    {

    }

    private function buildContent()
    {

    }

}
