<?php

namespace Smalot\Git;

/**
 * Class Repository
 *
 * @package Smalot\Git
 */
class Repository
{
    const BRANCH_ALL = 'all';

    const BRANCH_LOCAL = 'local';

    const BRANCH_REMOTE = 'remote';

    /**
     * @var string
     */
    protected $path = null;

    /**
     * @var Branch[]
     */
    protected $branches = null;

    /**
     * @var Tag[]
     */
    protected $tags = null;

    /**
     * @param string $path
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $type
     *
     * @return Branch[]
     * @throws InvalidArgumentException
     */
    public function getBranches($type = self::BRANCH_LOCAL)
    {
        if (null === $this->branches) {
            $branches = array();

            // Local branches
            $command = new Command('branch', $this->path);
            $result  = $command->run();

            if (preg_match_all('/[\*\s]*([^\s]*)?( \-> (.*)?)?[\n\r]+/m', $result . "\n", $match)) {
                foreach ($match[1] as $branchName) {
                    $branches[$branchName] = new Branch($branchName, $this, false);
                }
            }

            // Remote branches
            // Local branches
            $command = new Command('branch -r', $this->path);
            $result  = $command->run();

            if (preg_match_all('/[\*\s]*([^\s]*)?( \-> (.*)?)?[\n\r]+/m', $result . "\n", $match)) {
                foreach ($match[1] as $branchName) {
                    $branches[$branchName] = new Branch($branchName, $this, true);
                }
            }

            $this->branches = $branches;
        }

        $branches = array();

        switch ($type) {
            case self::BRANCH_LOCAL:
                foreach ($this->branches as $branchName => $branch) {
                    if ($branch->isLocal()) {
                        $branches[$branchName] = $branch;
                    }
                }
                break;

            case self::BRANCH_REMOTE:
                foreach ($this->branches as $branchName => $branch) {
                    if ($branch->isRemote()) {
                        $branches[$branchName] = $branch;
                    }
                }
                break;

            case self::BRANCH_ALL:
                $branches = $this->branches;
                break;

            default:
                throw new InvalidArgumentException('Unknown branch type.');
        }

        return $branches;
    }

    /**
     * @return Branch
     */
    public function getCurrentBranch()
    {
        $command = new Command('branch', $this->path);
        $result  = $command->run();

        if (preg_match('/\*\s+([^\s]*)?( \-> (.*)?)?[\n\r]+/m', $result . "\n", $match)) {
            $branches = $this->getBranches();

            return $branches[$match[1]];
        }

        return null;
    }

    /**
     * @return Tag[]
     */
    public function getTags()
    {
        if (null === $this->tags) {
            $command    = new Command('tag -l', $this->path);
            $result     = $command->run();
            $this->tags = array();

            foreach (explode("\n", trim($result)) as $tagName) {
                $this->tags[$tagName] = new Tag($tagName, $this);
            }
        }

        return $this->tags;
    }

    /**
     * @param string $tagName
     *
     * @return Tag
     * @throws InvalidArgumentException
     */
    public function getTag($tagName)
    {
        $this->getTags();

        if (isset($this->tags[$tagName])) {
            return $this->tags[$tagName];
        }

        throw new InvalidArgumentException('Unknown tag name.');
    }

    /**
     * @param string $path
     * @param bool   $bareMode
     *
     * @return Repository
     * @throws RuntimeException
     */
    public static function create($path, $bareMode = false)
    {
        if ($bareMode && file_exists($path)) {
            throw new RuntimeException('The directory already exists.');
        }

        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        $command = new Command($bareMode ? 'init --bare --quiet' : 'init --quiet', $path);
        $command->run();

        return new self($path);
    }

    /**
     * @param string $path
     * @param string $repository
     * @param string $name
     * @param bool   $bareMode
     *
     * @return Repository
     * @throws RuntimeException
     */
    public static function cloneRepository($path, $repository, $name = null, $bareMode = false)
    {
        $path = rtrim($path, '/');

        if (null === $name) {
            if ($pos = max(strpos($repository, '@'), strpos($repository, ':'))) {
                $tmp = substr($repository, $pos + 1);
            } else {
                $tmp = $repository;
            }

            if ($bareMode) {
                $name = basename($tmp);
            } else {
                $name = basename($tmp, '.git');
            }
        }

        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        if (file_exists($path . '/' . $name)) {
            throw new RuntimeException('The destination already exists.');
        }

        $command = new Command(
            sprintf(
                'clone ' . ($bareMode ? '--bare ' : '') . '%s %s --quiet',
                escapeshellarg($repository),
                escapeshellarg($name)
            ),
            $path
        );
        $command->run();

        return new self($path . '/' . $name);
    }
}
