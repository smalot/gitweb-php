<?php

namespace Smalot\Git;

/**
 * Class Branch
 * @package Smalot\Git
 */
class Branch
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
     * @var bool
     */
    protected $isRemote = false;

    /**
     * @var \DateTime
     */
    protected $date = null;

    /**
     * @param string     $name
     * @param Repository $repository
     * @param bool       $isRemote
     */
    public function __construct($name, $repository = null, $isRemote = false)
    {
        $this->name       = $name;
        $this->repository = $repository;
        $this->isRemote   = $isRemote;
    }

    /**
     * @return bool
     */
    public function isLocal()
    {
        return !$this->isRemote;
    }

    /**
     * @return bool
     */
    public function isRemote()
    {
        return $this->isRemote;
    }
}
