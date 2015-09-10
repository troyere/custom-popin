<?php

namespace AppBundle\Services;

use Exception;
use Symfony\Component\Filesystem\Filesystem;
use AppBundle\Services\ConfigService;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

class ScriptService
{

    /**
     * @var Filesystem
     */
    protected $fileSystem;

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
    protected $template;

    /**
     * @var string
     */
    protected $dir;

    /**
     * @var string
     */
    protected $file;

    public function __construct(array $parameters, ConfigService $config, EngineInterface $engine)
    {
        $this->fileSystem = new Filesystem();
        $this->config     = $config;
        $this->engine     = $engine;
        $this->template   = $parameters['template'];
        $this->dir        = $parameters['dir'];
        $this->file       = $parameters['file'];
    }

    /**
     * Return the script path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->dir.DIRECTORY_SEPARATOR.$this->file;
    }

    /**
     * Create the script file
     *
     * @throws Exception
     */
    public function createFile()
    {
        // Script rendering
        $content = $this->engine->render($this->template, array(
            'config' => $this->config->get()
        ));

        // Script file creation
        $this->fileSystem->mkdir($this->dir);
        $path = $this->getPath();
        if (!file_put_contents($path, $content)) {
            throw new Exception('Something went wrong in the script creation.');
        }
        return $path;
    }

}
