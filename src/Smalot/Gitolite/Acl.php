<?php

namespace Smalot\Gitolite;

/**
 * Class Acl
 *
 * @package Smalot\Gitolite
 */
class Acl
{
    /**
     * @var string
     */
    protected $permission = null;

    /**
     * @var array
     */
    protected $refexes = null;

    /**
     * @var array
     */
    protected $members = null;

    /**
     * @param string $permission
     * @param array  $members
     * @param array  $referes
     */
    public function __construct($permission, $members, $referes = array())
    {
        $this->permission = $permission;

        if (!is_array($members)) {
            $this->members = array($members);
        } else {
            $this->members = $members;
        }

        if (!is_array($referes)) {
            $this->refexes = array($referes);
        } else {
            $this->refexes = $referes;
        }
    }

    /**
     * @param array $members
     */
    public function setMembers($members)
    {
        $this->members = $members;
    }

    /**
     * @return array
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * @param string $permission
     */
    public function setPermission($permission)
    {
        $this->permission = $permission;
    }

    /**
     * @return string
     */
    public function getPermission()
    {
        return $this->permission;
    }

    /**
     * @param array $refexes
     */
    public function setRefexes($refexes)
    {
        $this->refexes = $refexes;
    }

    /**
     * @return array
     */
    public function getRefexes()
    {
        return $this->refexes;
    }

    /**
     * @return string
     * @throws RuntimeException
     */
    public function render()
    {
        $members = array();

        foreach ($this->members as $member) {
            if ($member instanceof Group) {
                $members[] = '@' . $member->getName();
            } elseif ($member instanceof User) {
                $members[] = $member->getName();
            } else {
                throw new RuntimeException('Invalid member type.');
            }
        }

        $members = array_unique($members);
        $content = sprintf(
            "    %s = %s" . PHP_EOL,
            str_pad($this->permission . ' ' . implode(' ', $this->refexes), 6),
            implode(' ', $members)
        );

        return $content;
    }

//    /**
//     * @param $path
//     *
//     * @return array
//     */
//    public function load($path)
//    {
//        $filename = sprintf('%s/conf/repos/%s.conf', $path, 'accumulated'); //basename($this->repository->getPath()));
//
//        if (file_exists($filename)) {
//            $text         = file_get_contents($filename);
//            $this->rights = $this->parse($text);
//        } else {
//            $this->rights = array();
//        }
//
//        return $this->rights;
//    }
//
//    /**
//     * @param string $text
//     *
//     * @return array
//     * @throws RuntimeException
//     */
//    protected function parse($text)
//    {
//        $resources    = array();
//        $repositories = array();
//
//        while ($text = ltrim($text)) {
//
//            if (preg_match('/^#(.*?)[\n\r]+/s', $text, $match, PREG_OFFSET_CAPTURE)) {
//                $offset = $match[0][1] + strlen($match[0][0]);
////                echo "comment : " . $match[1][0] . "\n";
//            } elseif (preg_match('/^@(.*?)[\t ]*=[\t ]*(.*?)[\t ]*[\n\r]+/s', $text, $match, PREG_OFFSET_CAPTURE)) {
//                $offset = $match[0][1] + strlen($match[0][0]);
//                $this->resolveResources($match[1][0], $match[2][0], $resources);
//            } elseif (preg_match(
//                '/^repo[\t ]+([^\s]+)[\t ]*[\n\r]+((\s+.*?[\n\r]+)+)/s',
//              $text . PHP_EOL,
//                $match,
//                PREG_OFFSET_CAPTURE
//            )
//            ) {
//                $rules  = array();
//                $offset = $match[0][1] + strlen($match[0][0]);
//
//                preg_match_all(
//                    '/[\s]+([^\s]+)[\s]*([^=]*?)[\t ]*=[\t ]*(.*?)[\t ]*[\n\r]+/s',
//                    $match[2][0],
//                    $sub_match
//                );
//
//                foreach ($sub_match[1] as $pos => $right) {
//                    $rules[] = array(
//                        $right,
//                        preg_split('/\s+/', $sub_match[2][$pos], -1, PREG_SPLIT_NO_EMPTY),
//                        preg_split('/\s+/', $sub_match[3][$pos], -1, PREG_SPLIT_NO_EMPTY),
//                    );
//                }
//
//                $this->resolveRepositoryResources($match[1][0], $rules, $repositories, $resources);
//            } else {
//                throw new RuntimeException('Parsing error: unexpected line.');
//            }
//
//            if ($offset) {
//                $text = substr($text, $offset);
//            }
//        }
//
//        return $repositories;
//    }
//
//    /**
//     * @param string $name
//     * @param string $content
//     * @param array  $resources
//     *
//     * @return array
//     * @throws RuntimeException
//     */
//    protected function resolveResources($name, $content, &$resources)
//    {
//        if (isset($resources[$name])) {
//            $resource = $resources[$name];
//        } else {
//            $resource = array();
//        }
//
//        $values = preg_split('/[\s]/', $content, -1, PREG_SPLIT_NO_EMPTY);
//
//        foreach ($values as $value) {
//            if ($value[0] == '@') {
//                if (isset($resources[substr($value, 1)])) {
//                    $resource = array_merge($resource, $resources[substr($value, 1)]);
//                } else {
//                    throw new RuntimeException('Parsing error: reference to missing resource.');
//                }
//            } else {
//                $resource[] = $value;
//            }
//        }
//
//        return $resources[$name] = array_unique($resource);
//    }
//
//    /**
//     * @param string $type
//     * @param array  $names
//     * @param array  $resources
//     *
//     * @return array
//     * @throws RuntimeException
//     */
//    protected function getResourcesByType($type, $names, $resources)
//    {
//        if (in_array('@all', $names)) {
//            if ($type == 'user') {
//                return $this->getAllUsers();
//            } else {
//                return $this->getAllRepositories();
//            }
//        }
//
//        $return = array();
//
//        foreach ($names as $name) {
//            if ($name[0] == '@') {
//                if (isset($resources[substr($name, 1)])) {
//                    $return = array_merge($return, $resources[substr($name, 1)]);
//                } else {
//                    throw new RuntimeException('Parsing error: reference to missing resource.');
//                }
//            } else {
//                $return[] = $name;
//            }
//        }
//
//        return array_unique($return);
//    }
//
//    /**
//     * @param string $name
//     * @param array  $rules
//     * @param array  $repositories
//     * @param array  $resources
//     *
//     * @throws RuntimeException
//     */
//    protected function resolveRepositoryResources($name, $rules, &$repositories, $resources)
//    {
//        $targetRepositories = $this->getResourcesByType('repo', array($name), $resources);
//
//        foreach ($targetRepositories as $targetRepository) {
//            foreach ($rules as $rule) {
//                list($right, $refs, $targets) = $rule;
//
//                if (!$refs) {
//                    $refs = array(self::REFS_ALL);
//                }
//
//                $targetUsers = $this->getResourcesByType('user', $targets, $resources);
//
//                foreach ($refs as $ref) {
//                    foreach ($targetUsers as $targetUser) {
//                        if ($right == self::RIGHT_NONE) {
//                            unset($targetRepository[$targetUser][$ref]);
//                        }
//
//                        $repositories[$targetRepository][$targetUser][$ref][$right] = $right;
//                    }
//                }
//            }
//        }
//    }
//
//    /**
//     * @return array
//     */
//    protected function getAllRepositories()
//    {
//        if (null === self::$repositories) {
//            $command = new Command('list-phy-repos');
//            $results = $command->run();
//
//            self::$repositories = preg_split('/\s/s', $results, -1, PREG_SPLIT_NO_EMPTY);
//        }
//
//        return self::$repositories;
//    }
//
//    /**
//     * @return array
//     */
//    protected function getAllUsers()
//    {
//        if (null === self::$users) {
//            $command = new Command('list-users');
//            $results = $command->run();
//
//            $users       = preg_split('/\s/s', $results, -1, PREG_SPLIT_NO_EMPTY);
//            self::$users = array();
//
//            foreach ($users as $user) {
//                if ($user[0] != '@') {
//                    self::$users[] = $user;
//                }
//            }
//        }
//
//        return self::$users;
//    }
}
