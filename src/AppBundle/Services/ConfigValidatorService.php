<?php

namespace AppBundle\Services;

class ConfigValidatorService
{

    /**
     * @var ScriptImageService
     */
    protected $imageService;

    public function __construct(ScriptImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Config validation
     *
     * @param array $config
     * @return array
     */
    public function validate(array $config)
    {
        $title     = isset($config['title']) ? $config['title'] : null;
        $text      = isset($config['text']) ? $config['text'] : null;
        $sizeMode  = isset($config['sizeMode']) ? $config['sizeMode'] : null;
        $width     = isset($config['width']) ? $config['width'] : null;
        $height    = isset($config['height']) ? $config['height'] : null;
        $imagePath = isset($config['imagePath']) ? $config['imagePath'] : null;

        $errors = array();
        if (empty($title)) {
            $errors[] = 'Title is required.';
        }
        if (empty($text)) {
            $errors[] = 'Text is required.';
        }
        if ($sizeMode === 'custom' && (empty($width) || empty($height))) {
            $errors[] = 'Width and height are both required if the size mode equals "custom".';
        }
        if ($sizeMode !== 'custom' && (!empty($width) || !empty($height))) {
            $errors[] = 'Width and height are allowed only if the size mode equals "custom".';
        }
        if (!empty($width) && !is_numeric($width)) {
            $errors[] = 'Width must be an integer.';
        }
        if (!empty($height) && !is_numeric($height)) {
            $errors[] = 'Height must be an integer.';
        }
        if (!empty($imagePath)) {
            $allowedType = array(
                'image/jpeg',
                'image/jpg',
                'image/png',
                'image/gif',
            );
            $type = $this->imageService->getMimeType($imagePath);
            if (!in_array($type, $allowedType)) {
                $errors[] = sprintf('Wrong type (%s). The file must be an image (jpeg, png, gif).', $type);
            }
        }
        return $errors;
    }

}
