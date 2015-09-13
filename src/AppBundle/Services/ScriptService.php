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
     * @var EngineInterface
     */
    protected $engine;

    /**
     * @var ConfigService
     */
    protected $configService;

    /**
     * @var ScriptImageService
     */
    protected $imageService;

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

    public function __construct(array $parameters, EngineInterface $engine, ConfigService $configService, ScriptImageService $imageService)
    {
        $this->fileSystem    = new Filesystem();
        $this->engine        = $engine;
        $this->configService = $configService;
        $this->imageService  = $imageService;
        $this->template      = $parameters['template'];
        $this->dir           = $parameters['dir'];
        $this->file          = $parameters['file'];
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
        $content = $this->engine->render($this->template, $this->renderParams());

        // Script file creation
        $this->fileSystem->mkdir($this->dir);
        $path = $this->getPath();
        if (!file_put_contents($path, $content)) {
            throw new Exception('Something went wrong in the script creation.');
        }
        return $path;
    }

    /**
     * Prepare the parameters for the template
     *
     * @return array
     * @throws Exception
     */
    private function renderParams()
    {
        $config = $this->configService->get();

        // Sizes
        switch ($config['sizeMode']) {
            case 'small':
                $size = array('width'  => '300', 'height' => '121');
                break;
            case 'normal':
                $size = array('width'  => '600', 'height' => '121');
                break;
            case 'large':
                $size = array('width'  => '900', 'height' => '121');
                break;
            default:
                $size = array('width' => $config['width'], 'height' => $config['height']);
                break;
        }

        // Css styles
        if ($config['sizeMode'] === 'full-page') {
            $styleDialog = array(
                'top'    => '0',
                'left'   => '0',
                'width'  => '100%',
                'height' => '100%',
                'margin' => '0',
            );
        } else {
            $styleDialog = array(
                'top'    => '15%',
                'left'   => '50%',
                'width'  => $size['width'].'px',
                'margin' => '0 0 0 -'.($size['width'] / 2).'px',
            );
            if (isset($config['contentBehavior'])) {
                switch ($config['contentBehavior']) {
                    case 'adaptive-height':
                        $styleDialog['min-height'] = $size['height'].'px';
                        break;
                    case 'vertical-scroll':
                        $styleDialog['height'] = $size['height'].'px';
                        $styleDialog['overflow-y'] = 'scroll';
                        break;
                    case 'none':
                    default:
                        $styleDialog['height'] = $size['height'].'px';
                        break;
                }
            }
        }
        $strStyleDialog = '';
        foreach ($styleDialog as $property => $value) {
            $strStyleDialog .= $property.':'.$value.'; ';
        }
        $config['styleDialog'] = $strStyleDialog;

        $styleText = array();
        if ($config['theme'] !== 'none') {
            $styleText = array(
                'padding' => '15px',
                'border' => '1px solid transparent',
                'border-radius' => '4px',
            );
        }
        switch ($config['theme']) {
            case 'danger':
                $styleText['color'] = '#a94442';
                $styleText['background-color'] = '#f2dede';
                $styleText['border-color'] = '#ebccd1';
                break;
            case 'warning':
                $styleText['color'] = '#8a6d3b';
                $styleText['background-color'] = '#fcf8e3';
                $styleText['border-color'] = '#faebcc';
                break;
            case 'info':
                $styleText['color'] = '#31708f';
                $styleText['background-color'] = '#d9edf7';
                $styleText['border-color'] = '#bce8f1';
                break;
            case 'success':
                $styleText['color'] = '#3c763d';
                $styleText['background-color'] = '#dff0d8';
                $styleText['border-color'] = '#d6e9c6';
                break;
           case 'none': default:
                break;
        }
        $strStyleText = '';
        foreach ($styleText as $property => $value) {
            $strStyleText .= $property.':'.$value.'; ';
        }
        $config['styleText'] = $strStyleText;

        // Image resizing
        if (isset($config['image']) && !empty($config['image'])) {
            $config['image'] = $this->imageService->resize($config['image'], 150, 150);
        }

        return $config;
    }

}
