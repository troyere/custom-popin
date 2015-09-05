<?php

namespace AppBundle\Services;

use Predis\ClientInterface;

class ConfigService
{

    /**
     * @var ClientInterface
     */
    protected $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Save all the config values
     *
     * @param array $config
     */
    public function save(array $config)
    {
        foreach ($config as $key => $value) {
            $this->client->set($key, $value);
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
            $result[$key] = $this->client->get($key);
        }
        return $result;
    }

    /**
     * Get stored keys
     *
     * @return array
     */
    private function getKeys()
    {
        return $this->client->keys('*');
    }

}
