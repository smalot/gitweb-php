<?php

namespace Smalot\Gitolite;

/**
 * Class Repository
 *
 * @package Smalot\Gitolite
 */
class Repository implements NameInterface
{
    /**
     * @var string
     */
    protected $name = null;

    /**
     * @var Acl[]
     */
    protected $acls = null;

    /**
     * @param string $name
     * @param Acl[]  $acls
     */
    public function __construct($name, $acls = array())
    {
        $this->name = $name;
        $this->acls = $acls;
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
     * @param Acl $acl
     */
    public function addAcl(Acl $acl)
    {
        $this->acls[] = $acl;
    }

    /**
     * @param \Smalot\Gitolite\Acl[] $acls
     */
    public function setAcls($acls)
    {
        $this->acls = array();

        foreach ($acls as $acl) {
            $this->addAcl($acl);
        }
    }

    /**
     * @return \Smalot\Gitolite\Acl[]
     */
    public function getAcls()
    {
        return $this->acls;
    }

    /**
     * @return string
     */
    public function render()
    {
        if (count($this->acls)) {
            $content = 'repo ' . $this->name . PHP_EOL;

            foreach ($this->acls as $acl) {
                $content .= $acl->render();
            }

            return $content . PHP_EOL;
        } else {
            return '';
        }
    }
}
