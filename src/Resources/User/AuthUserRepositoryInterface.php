<?php
declare(strict_types=1);

/*
 * This file is part of the some package.
 * (c) Jakub Janata <jakubjanata@gmail.com>
 * For the full copyright and license information, please view the LICENSE file.
 */

namespace PgDev\AuthClient\Resources\User;

/**
 * @author Jakub Janata <jakubjanata@gmail.com>
 */
interface AuthUserRepositoryInterface
{
    /**
     * @param UserDTO $userDTO
     * @return mixed
     */
    public function findOrCreateByExternalIdentifier(UserDTO $userDTO);
}
