<?php

include 'vendor/autoload.php';

echo '<pre>';

\Smalot\Gitolite\Command::setAlterCommandCallback(
    function ($command, $gitolite) {
        return sprintf('export HOME=%s && %s %s 2>&1', escapeshellarg(getenv('HOME')), escapeshellcmd($gitolite), $command);
    }
);
//\Smalot\Gitolite\Command::setAlterCommandCallback(null);
//$command = new \Smalot\Gitolite\Command('query-rc');
//var_dump($command->run(false));

$config = new \Smalot\Gitolite\Config();
var_dump($config->getVariable('POST_COMPILE'));

$repository = \Smalot\Git\Repository::cloneRepository('/tmp/', 'git@localhost:gitolite-admin.git');
var_dump($repository);

//echo `set`;
//echo `id`;
//echo `export HOME=/home/git`;
//echo `export HOME=/home/git && /home/git/bin/gitolite 2>&1`;
//echo `/home/git/bin/gitolite query-rc -a 2>&1`;

//phpinfo();

//$repository = \Smalot\Git\Repository::create('/tmp/test_git');
//var_dump($repository);
//
//$repository = \Smalot\Git\Repository::cloneRepository('/tmp/', 'git@github.com:smalot/magento-client.git', null);
//var_dump($repository);

//$repository = new \Smalot\Git\Repository('/tmp/magento-client');
//$branches   = $repository->getBranches();
//var_dump($branches);
//$branches   = $repository->getBranches(\Smalot\Git\Repository::BRANCH_REMOTE);
//var_dump($branches);
//$branches   = $repository->getBranches(\Smalot\Git\Repository::BRANCH_ALL);
//var_dump($branches);
//$branch   = $repository->getCurrentBranch();
//var_dump($branch);
//$tags     = $repository->getTags();
//$tagNames = array_keys($tags);
//$tag      = $repository->getTag($tagNames[5]);
//$tag->load();
//$commit   = $tag->getAttribute('commit');
//$commit->load();
//var_dump($commit);
//$message   = $tag->getAttribute('message');
//var_dump($message);
