<?php

namespace Smalot\Gitolite;

/**
 * Class PublicKey
 *
 * @package Smalot\Gitolite
 */
class PublicKey
{
    /**
     * @var string
     */
    protected $name = null;

    /**
     * @var string
     */
    protected $key = null;

    /**
     * @param string $name
     * @param string $key
     */
    public function __construct($name, $key = '')
    {
        $this->name = $name;
        $this->key  = $key;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param $file
     */
    public function load($file)
    {
        $this->key = file_get_contents($file);
    }

    /**
     * @param $file
     */
    public function save($file)
    {
        if ($this->key) {
            $dir = dirname($file);
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }

            file_put_contents($file, $this->key);
        }
    }
}
