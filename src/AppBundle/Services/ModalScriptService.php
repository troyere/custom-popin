<?php

namespace AppBundle\Services;

use AppBundle\Services\ConfigService;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

class ModalScriptService
{

    /**
     * @var ConfigService
     */
    protected $config;

    /**
     * @var EngineInterface
     */
    protected $engine;

    /**
     * @var string
     */
    protected $dir;

    /**
     * @var string
     */
    protected $template;

    public function __construct(ConfigService $config, EngineInterface $engine, $dir, $template)
    {
        $this->config   = $config;
        $this->engine   = $engine;
        $this->dir      = $dir;
        $this->template = $template;
    }

    /**
     * Return the modal script dir
     *
     * @return string
     */
    public function getDir()
    {
        return $this->dir;
    }

    /**
     * Return the modal script path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->dir.DIRECTORY_SEPARATOR.'generated-page.html';
    }

    /**
     * Create the modal file
     *
     * @throws \Exception
     */
    public function createFile()
    {
        $dir    = $this->getDir();
        $dest   = $this->getPath();
        $config = $this->config->get();

        $content = $this->engine->render($this->template, array(
            'config' => $config
        ));

        if (!is_dir($dir)) {
            mkdir($dir);
        }
        if (!file_put_contents($dest, $content)) {
            throw new \Exception('Something went wrong in the modal script creation.');
        }
    }

}
