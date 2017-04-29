<?php
declare(strict_types=1);

/*
 * This file is part of the some package.
 * (c) Jakub Janata <jakubjanata@gmail.com>
 * For the full copyright and license information, please view the LICENSE file.
 */

namespace PgDev\AuthClient\DI;

use Nette\DI\CompilerExtension;
use Nette\DI\Config\Helpers;
use Nette\Utils\Validators;
use PgDev\AuthClient\OAuth2\UserLoginService;

/**
 * @author Jakub Janata <jakubjanata@gmail.com>
 */
class AuthClientExtension extends CompilerExtension
{
    /** @var array */
    public $defaultConfig = [
        'authServer' => [
            'urlAuthorize' => 'https://pg-auth.ifire.cz/oauth2/v1/authorize',
            'urlAccessToken' => 'https://pg-auth.ifire.cz/oauth2/v1/access-token',
            'urlResourceOwnerDetails' => 'https://pg-auth.ifire.cz/oauth2/v1/details'
        ],
        'resourceServer' => [
            'baseUri' => 'https://pg-auth.ifire.cz'
        ],
        'client' => [
            'redirectUri' => null,
            'clientId' => null,
            'clientSecret' => null,
            'scopes' => []
        ]
    ];

    /**
     *
     */
    public function loadConfiguration(): void
    {
        $config = Helpers::merge($this->getConfig(), $this->defaultConfig);
        $this->setConfig($config);

        Validators::assert($config['client']['redirectUri'], 'url');
        Validators::assert($config['client']['clientId'], 'scalar');
        Validators::assert($config['client']['clientSecret'], 'scalar');

        $builder = $this->getContainerBuilder();

        $builder->addDefinition($this->prefix('userLoginService'))
            ->setClass(UserLoginService::class)
            ->setArguments([$this->config]);
    }
}
