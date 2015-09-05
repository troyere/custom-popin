<?php

namespace AppBundle\Services;

use Exception;
use Predis\ClientInterface;

class ConfigService
{

    /**
     * @var ClientInterface
     */
    protected $redisClient;

    /**
     * @var ImageService
     */
    protected $imageService;

    public function __construct(ClientInterface $redisClient, ImageService $imageService)
    {
        $this->redisClient  = $redisClient;
        $this->imageService = $imageService;
    }

    /**
     * Save all the config values
     *
     * @param array $config
     */
    public function save(array $config)
    {
        foreach ($config as $key => $value) {
            if ($key === 'image') {
                $this->redisClient->set('reducedImage', $this->imageService->resizeHeight($value, 25));
            }
            $this->redisClient->set($key, $value);
        }
    }

    /**
     * Get all the config values
     *
     * @return array
     */
    public function get()
    {
        $result = array();
        $keys   = $this->getKeys();
        foreach ($keys as $key) {
            $result[$key] = $this->redisClient->get($key);
        }
        return $result;
    }

    /**
     * Delete all the config values
     *
     * @return int
     * @throws Exception
     */
    public function clear()
    {
        $keys = $this->getKeys();
        if (!count($keys)) {
            throw new Exception('Nothing to delete.');
        }
        return $this->redisClient->del($keys);
    }

    /**
     * Get stored keys
     *
     * @return array
     */
    private function getKeys()
    {
        return $this->redisClient->keys('*');
    }

}
