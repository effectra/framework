<?php

declare(strict_types=1);

namespace Effectra\Core\Auth;

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
        $credentials = 'email = :email';

        $query = Query::select(static::$table)->where($credentials)->limit(1);
        $result = static::db()->withQuery($query)->get(['email' => $email]);
        if ($result && !empty($result)) {
            return (object) $result[0];
        }

        return null;
    }

    /**
     * Get a user record by token.
     *
     * @param string $token The token to search for.
     * @return mixed The user record.
     */
    public static function getToken($token)
    {
        $credentials = 'token = :token';

        $query = Query::select(static::$table)->where($credentials)->limit(1);

        $result = static::db()->withQuery($query)->get(['token' => $token]);

        return $result[0];
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

        $query = Query::update(static::$table)
            ->columns(['password'])
            ->values([':password'])
            ->where(['id' => $id]);

        $result = static::db()->withQuery($query)->run(['password' => $newPassword]);

        return $result;
    }

    /**
     * Verify a user's email.
     *
     * @param string $email The email to verify.
     * @return mixed The result of the update operation.
     */
    public static function verifyEmail($email)
    {
        $query = Query::update(static::$table)
            ->columns(['verified', 'email_verified_at', 'updated_at'])
            ->values([':verified', ':email_verified_at', 'updated_at'])
            ->where(['email' => $email]);

        $result = static::db()->withQuery($query)->run([
            'verified' => 1,
            'email_verified_at' => Query::CURRENT_TIMESTAMP,
            'updated_at' => Query::CURRENT_TIMESTAMP
        ]);

        return $result;
    }

    /**
     * Reset a user's Token.
     *
     * @param string $newToken The new token.
     * @param int|string $id The user ID.
     * @return bool The result of the update operation.
     */
    public static function updateToken(string $newToken,int|string $id):bool
    {
        $query = Query::update(static::$table)->columns(['token', 'updated_at'])->values([':token', ':updated_at'])->where(['id' => ':id']);

        $result = static::db()->withQuery($query)->run([
            'id' => $id,
            'token' => $newToken,
            'updated_at' => Query::CURRENT_TIMESTAMP
        ]);

        return $result;
    }
}
