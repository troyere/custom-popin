<?php

namespace AppBundle\Services;

class ConfigValidatorService
{

    /**
     * @var ImageService
     */
    protected $imageService;

    public function __construct(ImageService $imageService)
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
        $sizeMode = isset($config['sizeMode']) ? $config['sizeMode'] : null;
        $width    = isset($config['width']) ? $config['width'] : null;
        $height   = isset($config['height']) ? $config['height'] : null;
        $image    = isset($config['image']) ? $config['image'] : null;

        $errors = array();
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
        if (!empty($image)) {
            $allowedType = array(
                'image/jpeg',
                'image/jpg',
                'image/png',
                'image/gif',
            );
            $type = $this->imageService->getMimeType($image);
            if (!in_array($type, $allowedType)) {
                $errors[] = sprintf('Wrong type (%s). The file must be an image (jpeg, png, gif).', $type);
            }
        }
        return $errors;
    }

}
