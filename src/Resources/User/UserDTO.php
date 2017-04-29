<?php
declare(strict_types=1);

/*
 * This file is part of the some package.
 * (c) Jakub Janata <jakubjanata@gmail.com>
 * For the full copyright and license information, please view the LICENSE file.
 */

namespace PgDev\AuthClient\Resources\User;

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
     * @param string $jsonString
     * @return UserDTO
     * @throws OAuth2Exception
     */
    public static function createFromJsonString(string $jsonString): self
    {
        try {
            $data = Json::decode($jsonString);
        } catch (JsonException $e) {
            throw new OAuth2Exception('Invalid json');
        }

        $user = new self;
        $user->id = $data->id;
        $user->email = $data->email;
        $user->name = $data->name;
        $user->surname = $data->surname;
        $user->accessToken = $data->accessToken;

        return $user;
    }
}
