<?php

namespace Smalot\Gitolite;

/**
 * Class Command
 *
 * @package Smalot\Gitolite
 */
class Command
{
    /**
     * @var string
     */
    protected static $gitolite = '/home/git/bin/gitolite';

    /**
     * @var callable
     */
    protected static $callback = null;

    /**
     * @var string
     */
    protected $command = null;

    /**
     * @var bool
     */
    protected $debug = false;

    /**
     * @param string $command
     * @param bool   $debug
     */
    public function __construct($command, $debug = false)
    {
        $this->command = $command;
        $this->debug   = $debug;
    }

    /**
     * @param $path
     *
     * @throws RuntimeException
     */
    public static function setGitolitePath($path)
    {
        if (!$path || !is_file($path) || !is_executable($path)) {
            throw new InvalidArgumentException('Missing gitolite path.');
        }

        self::$gitolite = $path;
    }

    /**
     * @param callable $closure
     */
    public static function setAlterCommandCallback($closure)
    {
        if (null !== $closure && !is_callable($closure)) {
            throw new InvalidArgumentException('Invalid callback.');
        }

        self::$callback = $closure;
    }

    /**
     * @return string
     */
    protected static function buildCommand($command, $gitolite)
    {
        return sprintf('%s %s 2>&1', escapeshellcmd($gitolite), $command);
    }

    /**
     * @param bool $checkReturn
     *
     * @return string
     * @throws RuntimeException
     */
    public function run($checkReturn = true)
    {
        if (null !== self::$callback) {
            $closure = self::$callback;
            $command = $closure($this->command, self::$gitolite);
        } else {
            $command = self::buildCommand($this->command, self::$gitolite);
        }

        ob_start();
        passthru($command, $returnVar);
        $output = ob_get_clean();

        if ($this->debug) {
            echo 'command: ' . $command . "\n";
            echo "output:\n";
            echo trim($output) . "\n";
            echo "---------------------------\n";
        }

        if ($returnVar && $checkReturn) {
            throw new RuntimeException(sprintf(
                'Command %s failed with code %s: %s',
                $command,
                $returnVar,
                $output
            ), $returnVar);
        }

        return trim($output);
    }
}
