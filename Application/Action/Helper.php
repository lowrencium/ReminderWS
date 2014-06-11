<?php
class Helper
{
    public static function tokenManager($token)
    {
        session_id($token);
        return session_start();
    }
}