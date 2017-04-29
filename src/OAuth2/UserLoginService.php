<?php
declare(strict_types=1);

/*
 * This file is part of the some package.
 * (c) Jakub Janata <jakubjanata@gmail.com>
 * For the full copyright and license information, please view the LICENSE file.
 */

namespace PgDev\AuthClient\OAuth2;

use Exception;
use GuzzleHttp\Client;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Token\AccessToken;
use Nette\Http\Session;
use Nette\Http\SessionSection;
use Nette\SmartObject;
use PgDev\AuthClient\Exceptions\OAuth2Exception;
use PgDev\AuthClient\Resources\User\AuthUserRepositoryInterface;
use PgDev\AuthClient\Resources\User\UserDTO;

/**
 * @author Jakub Janata <jakubjanata@gmail.com>
 */
class UserLoginService
{
    use SmartObject;

    /** @var array */
    private $config;

    /** @var Session */
    private $session;

    /** @var GenericProvider */
    private $oAuthProvider;

    /** @var AuthUserRepositoryInterface */
    private $userRepository;

    /**
     * OAuth2Facade constructor.
     * @param array $config
     * @param Session $session
     * @param AuthUserRepositoryInterface $userRepository
     */
    public function __construct(array $config, Session $session, AuthUserRepositoryInterface $userRepository)
    {
        $this->config = $config;
        $this->session = $session;
        $this->userRepository = $userRepository;
    }

    /**
     * @return string
     */
    public function getAuthorizationUrl(): string
    {
        $provider = $this->getOAuthProvider();
        $authorizationUrl = $provider->getAuthorizationUrl(
            ['scope' => implode(' ', $this->config['client']['scopes'])]
        );

        $this->getSessionSection()->oauth2state = $provider->getState();

        return $authorizationUrl;
    }

    /**
     * @param string $code
     * @param string $state
     * @return mixed
     * @throws OAuth2Exception
     */
    public function requestTokenAndGetUser(string $code, string $state)
    {
        $provider = $this->getOAuthProvider();
        $section = $this->getSessionSection();

        // Check given state against previously stored one to mitigate CSRF attack
        if ($state !== $section->oauth2state) {
            unset($section->oauth2state);
            throw new OAuth2Exception('Error CSRF');
        }

        try {
            // Try to get an access token using the authorization code grant.
            $accessToken = $provider->getAccessToken('authorization_code', ['code' => $code]);

            $userData = $this->getUserInfo($accessToken);
            return $this->userRepository->findOrCreateByExternalIdentifier($userData);
            //
        } catch (IdentityProviderException $e) {
            throw new OAuth2Exception('Error token');
        } catch (Exception $e) {
            throw new OAuth2Exception($e->getMessage());
        }
    }

    /**
     * @param AccessToken $accessToken
     * @return UserDTO
     */
    private function getUserInfo(AccessToken $accessToken): UserDTO
    {
        $provider = $this->getOAuthProvider();

        $request = $provider->getAuthenticatedRequest(
            'GET',
            $this->config['resourceServer']['baseUri'] . '/api/v1/user/',
            $accessToken
        );

        return UserDTO::createFromJsonString((string) (new Client)->send($request)->getBody());
    }

    /**
     * @return GenericProvider
     */
    private function getOAuthProvider(): GenericProvider
    {
        if ($this->oAuthProvider === null) {
            $this->oAuthProvider = new GenericProvider($this->config['authServer'] + $this->config['client']);
        }

        return $this->oAuthProvider;
    }

    /**
     * @return SessionSection|\stdClass
     */
    private function getSessionSection(): SessionSection
    {
        return $this->session->getSection('oauth2');
    }
}
