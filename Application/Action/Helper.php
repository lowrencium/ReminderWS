<?php
class Helper
{
    /**
     * @param string $token
     * @return bool
     */
    public static function tokenManager($token)
    {
        session_id($token);
        return session_start();
    }

    /**
     * @param int $password
     * @return int string
     */
    public static function encodePassword($password)
    {
        $digest = hash("sha512", $password, true);

        for ($i = 1; $i < 5000; $i++) {
            $digest = hash("sha512", $digest.$password, true);
        }

        return base64_encode($digest);
    }
}