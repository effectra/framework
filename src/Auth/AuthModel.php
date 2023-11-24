<?php

declare(strict_types=1);

namespace Effectra\Core\Auth;

use Effectra\Contracts\Database\DBInterface;
use Effectra\SqlQuery\Condition;
use Effectra\SqlQuery\Query;

/**
 * Trait AuthModel
 *
 * Provides database-related methods for authentication models.
 */
trait AuthModel
{

    /**
     * Get a user record by email.
     *
     * @param string $email The email to search for.
     * @return object|null The user record if found, or null otherwise.
     */
    public static function getEmail($email)
    {
        
    }

    /**
     * Get a user record by token.
     *
     * @param string $token The token to search for.
     * @return mixed The user record.
     */
    public static function getToken($token)
    {
        
    }

    /**
     * Reset a user's password.
     *
     * @param int $id The user ID.
     * @param string $newPassword The new password.
     * @return mixed The result of the update operation.
     */
    public static function resetPassword($id, $newPassword)
    {

      
    }

    /**
     * Verify a user's email.
     *
     * @param string $email The email to verify.
     * @return mixed The result of the update operation.
     */
    public static function verifyEmail($email)
    {
       
    }

    /**
     * Reset a user's Token.
     *
     * @param string $newToken The new token.
     * @param int|string $id The user ID.
     * @return bool The result of the update operation.
     */
    public static function updateToken(string $newToken, int|string $id)
    {
        
    }
}
