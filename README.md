Quickstart
==========

[![Build Status](https://travis-ci.org/pg-dev/auth-client.svg?branch=master)](https://travis-ci.org/pg-dev/auth-client)
[![Coverage Status](https://coveralls.io/repos/github/pg-dev/auth-client/badge.svg?branch=master)](https://coveralls.io/github/pg-dev/auth-client?branch=master)

Installation
------------

The best way to install PgDev/AuthClient is using  [Composer](http://getcomposer.org/):

```sh
$ composer require pg-dev/auth-client
```

With Nette `2.4` and newer, you can enable the extension using your neon config.

```yml
extensions:
    pgDevClient: PgDev\AuthClient\DI\AuthClientExtension

pgDevClient:
    client:
        redirectUri: 'http://test.com/auth'
        clientId: *** id ***
        clientSecret: *** secret ***
        scopes:
            - read_user_info
            - ...
```

Documentation
-------------

TODO