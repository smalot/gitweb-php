<?php

namespace Smalot\Gitolite;

/**
 * Class User
 *
 * @package Smalot\Gitolite
 */
class User implements NameInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var PublicKey[]
     */
    protected $keys;

    /**
     * @param string      $name
     * @param string      $email
     * @param PublicKey[] $keys
     */
    public function __construct($name = '', $email = '', $keys = array())
    {
        $this->name  = $name;
        $this->email = $email;
        $this->keys  = $keys;
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
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param PublicKey $key
     */
    public function addKey($key)
    {
        $this->keys[$key->getName()] = $key;
    }

    /**
     * @return PublicKey[]
     */
    public function getKeys()
    {
        return $this->keys;
    }

    /**
     * @param string $name
     */
    public function removeKey($name)
    {
        unset($this->keys[$name]);
    }

    /**
     * @param PublicKey[] $keys
     */
    public function setKeys($keys)
    {
        $this->keys = array();

        foreach ($keys as $key) {
            $this->addKey($key);
        }
    }

    /**
     * @param string $name
     *
     * @return PublicKey
     */
    public function getKey($name)
    {
        return $this->keys[$name];
    }

    /**
     * @param string $path
     */
    public function load($path)
    {
        $files = glob(sprintf('%s/%s@*.pub', rtrim($path, '/'), $this->cleanName($this->name)));
        $keys  = array();

        foreach ($files as $file) {
            list(, $name) = explode('@', basename($file, '.pub'));

            $publicKey = new PublicKey($name);
            $publicKey->load($file);
            $keys[] = $publicKey;
        }

        $this->keys = $keys;
    }

    /**
     * @params string $path
     */
    public function save($path)
    {
        foreach ($this->keys as $key) {
            $key->save(
                sprintf(
                    '%s/%s@%s.pub',
                    rtrim($path, '/'),
                    self::cleanName($this->name, false),
                    self::cleanName($key->getName(), true)
                )
            );
        }
    }

    /**
     * @param string $name
     * @param bool   $strict
     *
     * @return mixed
     */
    protected static function cleanName($name, $strict = true)
    {
        if ($strict) {
            return preg_replace('/[^A-Z0-9\-_]/i', '_', $name);
        } else {
            return preg_replace('/[^A-Z0-9\-_\.]/i', '_', $name);
        }
    }
}
