GitWeb-PHP
==========

Proof Of Concept on a web interface for managing git repositories on top of [GitOlite](https://github.com/sitaramc/gitolite/) written in PHP 5.3 (and higher).

Targeted features :
* List repositories stored in a Gitolite context
* List authorized users
* List users keys
* List repository information (last commits, branches, ...)
* Add/Update/Delete multiple keys by users
* Add/Update/Delete users groups
* Add/Delete/Fork repositories
* Handle merge request
* Manage Gitolite Acls

The aim of this POC is to create elementaries elements to be used into a [Symfony](http://symfony.com/) project which will bring security, log reporting, flexibility and many more ...

Documentation references :
* Basic syntax and include conf : http://gitolite.com/gitolite/syntax.html
* Group definition and 1-pass parsing : http://gitolite.com/gitolite/groups.html
* Support for multi-key by user : http://gitolite.com/gitolite/users.html#multi-key
* Rules accumulation : http://gitolite.com/gitolite/rules.html#permsum
* GitHub fork/merge process : https://help.github.com/articles/fork-a-repo
* ...

Currently, the POC is based on :
* https://github.com/sitaramc/gitolite
* https://github.com/rafaelgou/gitolite-php
* https://github.com/ornicar/php-git-repo
* ...
