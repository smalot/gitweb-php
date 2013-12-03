<?php

namespace Smalot\Git;

/**
 * Class Tag
 *
 * @package Smalot\Git
 */
class Tag
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
     * Avoid circular references
     */
    public function __destruct()
    {
        unset($this->repository);
        $this->repository = null;
    }

    /**
     *
     */
    public function load()
    {
        $command = new Command('show -s -t ' . escapeshellarg($this->name), $this->repository->getPath());
        $result  = $command->run();

        $this->parse($result);
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
     * @param string $text
     */
    protected function parse($text)
    {
        $data = array();

        if (preg_match('/\nTagger\:\s+(.*)?\n/m', $text, $match)) {
            $data['tagger'] = $match[1];
        }

        if (preg_match('/\nDate\:\s+(.*)?\n/m', $text, $match)) {
            $data['date'] = \DateTime::createFromFormat('D M d H:i:s Y O', $match[1]);
        }

        if (preg_match('/\ncommit\s+(.*)?\n/m', $text, $match)) {
            $data['commit'] = new Commit($match[1], $this->repository);
        }

        if (preg_match('/\nDate\:\s+.*?\n(.*)?\ncommit\s+/ms', $text, $match)) {
            $data['message'] = trim($match[1]);
        }

        $this->data = $data;
    }
}
