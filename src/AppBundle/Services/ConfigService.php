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

    public function __construct(ClientInterface $redisClient)
    {
        $this->redisClient = $redisClient;
    }

    /**
     * Save all the config values
     *
     * @param array $config
     */
    public function save(array $config)
    {
        foreach ($config as $key => $value) {
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
        $keys = $this->getKeys();
        if (!count($keys)) {
            return null;
        }
        $result = array();
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
