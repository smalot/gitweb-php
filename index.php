<?php

include 'vendor/autoload.php';

echo '<pre>';

\Smalot\Gitolite\Command::setAlterCommandCallback(
    function ($command, $gitolite) {
        return sprintf('export HOME=%s && %s %s 2>&1', escapeshellarg(getenv('HOME')), escapeshellcmd($gitolite), $command);
    }
);

$gitadmin_remote = 'git@localhost:gitolite-admin.git';
$gitadmin_local  = '/tmp/gitolite-admin';

if (file_exists($gitadmin_local)) {
    $repository = new \Smalot\Git\Repository($gitadmin_local);
} else {
    $repository = \Smalot\Git\Repository::cloneRepository(dirname($gitadmin_local), $gitadmin_remote);
}

//$acl = new \Smalot\Gitolite\Acl($repository);
//$rights = $acl->load($gitadmin_local);

//$user      = new \Smalot\Gitolite\User('smalot', 'smalot@actualys.com');
//$publicKey = new \Smalot\Gitolite\PublicKey('actualys', 'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQDHrRH6ArKtIGA2Hnicr+/EZdLdxFjcCJJyGGdPy09D4a+IiFAgx19Ioa8K/7rd9p3cgAng3jFHL0mAkvAOIGwyNBY/gtexfXDuHHoVWyYmahA7ejiWtFIG2H4mIOhu0TwLMGpCiuO+hSFjGft4/saCQNE3m9Rd7xBsXn5ffyRuprE72BwfEjob+B00tMPKKebFuo8VmngYwJZ7N30j2W9ZX1QrMXM2YEJm0zPQVkiOXGtMUtTnFCAL7CTf2yeesKqXREwTH1tvw49b+b/Ji3Y/kHlac9N7gWRZnxSQC0L/5+d0mH9f8zm/xx96n+4NzPUyeW6iVA2DXLz3H46O4fgN root@sebastien-debian');
//$user->addKey($publicKey);
//$publicKey = new \Smalot\Gitolite\PublicKey('home@foo.bar', 'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQC+zDlS8QKIZh17XNa8519oAEU+wyANBfJ/GG6qifrTIs0rmJf/AbrIfLSHHH3MtWij0CN7qVwow45OW3eO2g8Q6EDJAcnB5Bzt5f2s5zZ3hCMbhM8ylwxKegkNsBFHX+pIH8P0l6un/vx1VdDLUj5Y1tNrUacJ2rE30IoXxgrKsVRjtsyEBSh953bKmdSHyMfk2kWpZEwUKT9X5JfPOJguvPuNZap1GWluWsZ4+ZKrqeoYtSjEAJ8Z7NugKyyiqB7+lbn2/66tcnRpb3i6VY/FmDYV6h4Vq7YfeJYMeTT5rCmXyDDraM6180p3yCjfh7Jjqd0uTHnYpg6ldmZMq+6H git@sebastien-debian');
//$user->addKey($publicKey);
//$user->save('/tmp/gitolite-admin/keydir/');

$user = new \Smalot\Gitolite\User('smalot', 'smalot@actualys.com');
$user->load('/tmp/gitolite-admin/keydir/');
var_dump($user);

//$acl = new \Smalot\Gitolite\Acl();
//$acl->

$groupSymfony = new \Smalot\Gitolite\Group('symfony');
$groupSymfony->addMember($user);

$groupActualys = new \Smalot\Gitolite\Group('actualys');
$groupActualys->addMember($groupSymfony);

$repository = new \Smalot\Gitolite\Repository('gitolite-admin');
$acl = new \Smalot\Gitolite\Acl('R', $groupActualys);
$repository->addAcl($acl);
$acl = new \Smalot\Gitolite\Acl('RW+', $groupSymfony, 'head');
$repository->addAcl($acl);
$acl = new \Smalot\Gitolite\Acl('RW+C', $groupSymfony, array('head', 'tmp'));
$repository->addAcl($acl);
echo $repository->render();

//echo $groupActualys->render() . "\n";
//echo $groupSymfony->render() . "\n";


die('test');


foreach ($rights as $repo => $acls) {
    echo "<h3 style='border: 1px solid black'>$repo</h3>";
    echo "<table><tr><th>user</th><th>ref</th><th>acls</th></tr>";

    foreach ($acls as $user => $acl) {
        foreach ($acl as $ref => $levels) {
            echo "<tr><td>$user</td><td>$ref</td><td>".implode(', ', $levels)."</td></tr>";
        }
    }

    echo "</table>";
}

die('');

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
