<?php

namespace Smalot\Gitolite;

/**
 * Class Config
 *
 * @package Smalot\Gitolite
 */
class Config
{
    protected $variables = null;

    public function __construct()
    {

    }

    /**
     * @param bool $fresh
     *
     * @return mixed
     */
    public function getVariables($fresh = false)
    {
        if (null === $this->variables || $fresh) {
            $command   = new Command('query-rc -a');
            $result    = $command->run(false);
            $variables = array();

            preg_match_all('/([A-Za-z0-9\_]+)=([^\s]+)/ms', $result, $matches);

            foreach ($matches[0] as $match) {
                list($name, $value) = explode('=', $match, 2);

                if (preg_match('/^(ARRAY|HASH)\(0x[a-f0-9]+\)$/', $value)) {
                    $command = new Command('query-rc ' . escapeshellarg($name));
                    $result  = $command->run();
                    $values  = preg_split('/[\n\r]+/s', $result);

                    $variables[$name] = $values;
                } else {
                    $variables[$name] = $value;
                }
            }

            $this->variables = $variables;
        }

        return $this->variables;
    }

    /**
     * @param string $name
     * @param bool   $fresh
     *
     * @return mixed
     */
    public function getVariable($name, $fresh = false)
    {
        $this->getVariables($fresh);

        if (array_key_exists($name, $this->variables)) {
            return $this->variables[$name];
        }

        return null;
    }
}
