<?php
declare(strict_types=1);

/*
 * This file is part of the some package.
 * (c) Jakub Janata <jakubjanata@gmail.com>
 * For the full copyright and license information, please view the LICENSE file.
 */

namespace PgDev\AuthClient\Tests\Integration;

require __DIR__ . '/../bootstrap.php';

use Nette\Configurator;
use PgDev\AuthClient\OAuth2\UserLoginService;
use Tester\Assert;
use Tester\TestCase;

/**
 * @testCase
 */
class UserLoginServiceTest extends TestCase
{
    /**
     *
     */
    public function testInit(): void
    {
        // test create
        $configurator = new Configurator;
        $configurator->setTempDirectory(__DIR__ . '/../tmp');
        $configurator->addConfig(__DIR__ . '/../config.neon');
        $container = $configurator->createContainer();

        /** @var UserLoginService $userLoginService */
        $userLoginService = $container->getByType(UserLoginService::class);
        Assert::type(UserLoginService::class, $userLoginService);

        // test url
        $url = $userLoginService->getAuthorizationUrl();
        $pattern = '#^https:\/\/pg-auth.ifire.cz\/oauth2\/v1\/authorize\?' .
            'scope=read_user_info\%20read_global_info&' .
            'state=[a-z0-9]+&' .
            'response_type=code&' .
            'approval_prompt=auto&' .
            'redirect_uri=http\%3A\%2F\%2Ftest.com&' .
            'client_id=1$#';

        Assert::match($pattern, $url);
    }
}

(new UserLoginServiceTest)->run();
