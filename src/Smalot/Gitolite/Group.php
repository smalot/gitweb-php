<?php

namespace Smalot\Gitolite;

/**
 * Class Group
 *
 * @package Smalot\Gitolite
 */
class Group implements NameInterface
{
    const TYPE_USER = 'user';

    const TYPE_REPO = 'repo';

    const TYPE_UND = 'undefined';

    /**
     * @var string
     */
    protected $name = null;

    /**
     * @var string
     */
    protected $type = null;

    /**
     * @var NameInterface[]
     */
    protected $members = null;

    /**
     * @param string $name
     * @param string $type
     */
    public function __construct($name, $type = self::TYPE_UND, $members = array())
    {
        $this->name    = $name;
        $this->type    = $type;
        $this->members = $members;
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
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param NameInterface $member
     */
    public function addMember(NameInterface $member)
    {
        $this->members[$member->getName()] = $member;
    }

    /**
     * @param NameInterface|string $name
     */
    public function removeMember($name)
    {
        if ($name instanceof NameInterface) {
            $name = $name->getName();
        }

        unset($this->members[$name]);
    }

    /**
     * @param NameInterface[] $members
     */
    public function setMembers($members)
    {
        $this->members = array();

        foreach ($members as $member) {
            $this->addMember($member);
        }
    }

    /**
     * @return NameInterface[]
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * @return string
     */
    public function render()
    {
        $elements = array();

        foreach ($this->members as $member) {
            if ($member instanceof Group) {
                $elements[] = '@' . $member->getName();
            } else {
                $elements[] = $member->getName();
            }
        }

        if ($elements) {
            return '@' . $this->name . ' = ' . implode(' ', $elements);
        } else {
            return '';
        }
    }
}
