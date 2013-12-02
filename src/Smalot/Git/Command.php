<?php

namespace Smalot\Git;

/**
 * Class Command
 * @package Smalot\Git
 */
class Command
{
    /**
     * @var string
     */
    protected static $git = null;

    /**
     * @var string
     */
    protected $command = null;

    /**
     * @var string
     */
    protected $dir = null;

    /**
     * @var bool
     */
    protected $debug = false;

    /**
     * @param string $command
     * @param string $dir
     * @param bool   $debug
     */
    public function __construct($command, $dir = null, $debug = false)
    {
        $this->command = $command;
        $this->dir     = $dir;
        $this->debug   = $debug;

        if (is_null(self::$git)) {
            self::setGitPath(trim(`which git`));
        }
    }

    /**
     * @param $path
     *
     * @throws RuntimeException
     */
    public static function setGitPath($path)
    {
        if (!$path || !is_file($path) || !is_executable($path)) {
            throw new InvalidArgumentException('Missing git path.');
        }

        self::$git = $path;
    }

    /**
     * @param bool $checkReturn
     *
     * @return string
     * @throws RuntimeException
     */
    public function run($checkReturn = true)
    {
        if (is_null($this->dir)) {
            $command = sprintf('%s %s 2>&1', escapeshellcmd(self::$git), $this->command);
        } else {
            $command = sprintf(
                'cd %s && %s %s 2>&1',
                escapeshellcmd($this->dir),
                escapeshellcmd(self::$git),
                $this->command
            );
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
            // Git 1.5.x returns 1 when running "git status"
            if (1 === $returnVar && 0 === strncmp($this->command, 'status', 6)) {
                // it's ok
            } else {
                throw new RuntimeException(sprintf(
                    'Command %s failed with code %s: %s',
                    $command,
                    $returnVar,
                    $output
                ), $returnVar);
            }
        }

        return trim($output);
    }
}
