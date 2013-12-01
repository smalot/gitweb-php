<?php

include 'vendor/autoload.php';

//$repository = \Smalot\Git\Repository::create('/tmp/test_git');
//var_dump($repository);
//
//$repository = \Smalot\Git\Repository::cloneRepository('/tmp/', 'git@github.com:smalot/magento-client.git', null);
$repository = \Smalot\Git\Repository::cloneRepository('/home/smalot/repositories/smalot/', '/home/smalot/repositories/test.git', null, true);
var_dump($repository);

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
