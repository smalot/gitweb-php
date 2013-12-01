<?php

namespace Smalot\Git;

/**
 * Class Commit
 * @package Smalot\Git
 */
class Commit
{
    /**
     * @var Repository
     */
    protected $repository = null;

    /**
     * @var string
     */
    protected $name = null;

    /**
     * @var array
     */
    protected $data = null;

    /**
     * @param string     $name
     * @param Repository $repository
     */
    public function __construct($name, $repository = null)
    {
        $this->name       = $name;
        $this->repository = $repository;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->data;
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function getAttribute($name)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }

        return null;
    }

    /**
     *
     */
    public function load()
    {
        $command = new Command('show ' . escapeshellarg($this->name), $this->repository->getPath());
        $result  = $command->run();

        $this->parse($result);
    }

    /**
     * @param string $text
     */
    protected function parse($text)
    {
        $data = array();

        if (preg_match('/\Author\:\s+(.*)?\n/m', $text, $match)) {
            $data['author'] = $match[1];
        }

        if (preg_match('/\nDate\:\s+(.*)?\n/m', $text, $match)) {
            $data['date'] = \DateTime::createFromFormat('D M d H:i:s Y O', $match[1]);
        }

        if (preg_match('/\ncommit\s+(.*)?\n/m', $text, $match)) {
            $data['commit'] = new Commit($match[1], $this->repository);
        }

        if (preg_match('/\nDate\:\s+.*?\n(.*)?\ndiff\s\-\-git\s+/ms', $text, $match)) {
            $data['message'] = trim($match[1]);
        }

        $this->data = $data;
    }
}
