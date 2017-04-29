<?php
declare(strict_types=1);

/*
 * This file is part of the some package.
 * (c) Jakub Janata <jakubjanata@gmail.com>
 * For the full copyright and license information, please view the LICENSE file.
 */

namespace PgDev\AuthClient\Resources\User;

use Error;
use League\OAuth2\Client\Token\AccessToken;
use Nette\SmartObject;
use Nette\Utils\Json;
use Nette\Utils\JsonException;
use PgDev\AuthClient\Exceptions\OAuth2Exception;

/**
 * @author Jakub Janata <jakubjanata@gmail.com>
 */
class UserDTO
{
    use SmartObject;

    /** @var int */
    private $id;

    /** @var string */
    private $email;

    /** @var string */
    private $name;

    /** @var string */
    private $surname;

    /** @var string */
    private $accessToken;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSurname(): string
    {
        return $this->surname;
    }

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * @param AccessToken $accessToken
     * @param string $jsonString
     * @return UserDTO
     * @throws OAuth2Exception
     */
    public static function createFromJsonString(AccessToken $accessToken, string $jsonString): self
    {
        try {
            $data = Json::decode($jsonString);
        } catch (JsonException $e) {
            throw new OAuth2Exception('Invalid json');
        }

        try {
            $user = new self;
            $user->id = (int) $data->id;
            $user->email = $data->email;
            $user->name = $data->name;
            $user->surname = $data->surname;
            $user->accessToken = $accessToken->getToken();
        } catch (Error $e) {
            throw new OAuth2Exception('Can\'t parse data');
        }

        return $user;
    }
}
