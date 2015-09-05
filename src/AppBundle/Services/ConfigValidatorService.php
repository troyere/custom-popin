<?php

namespace AppBundle\Services;

class ConfigValidatorService
{

    /**
     * Config validation
     *
     * @param array $config
     * @return array
     */
    public function validate(array $config)
    {
        $errors = array();
        if (isset($config['sizeMode']) && $config['sizeMode'] === 'custom' &&
            (!isset($config['width']) || isset($config['height']))) {
            $errors[] = 'Width and height are both required if the size mode equals "custom".';
        }
        if (isset($config['width']) && !is_integer($config['width'])) {
            $errors[] = 'Width must be an integer.';
        }
        if (isset($config['height']) && !is_integer($config['height'])) {
            $errors[] = 'Height must be an integer.';
        }
        return $errors;
    }

}
