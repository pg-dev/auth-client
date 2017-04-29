<?php
declare(strict_types=1);

namespace PgDev\AuthClient\Tests\Mock;

use PgDev\AuthClient\Resources\User\AuthUserRepositoryInterface;
use PgDev\AuthClient\Resources\User\UserDTO;

/**
 * Class AuthUserRepository
 * @package PgDev\AuthClient\Tests\Mock
 */
class AuthUserRepositoryMock implements AuthUserRepositoryInterface
{
    /**
     * @param UserDTO $userDTO
     * @return mixed
     */
    public function findOrCreateByExternalIdentifier(UserDTO $userDTO)
    {
        return null;
    }
}
