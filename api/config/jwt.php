<?php

use Firebase\JWT\JWT;

class JWTUtil
{
    private static $key = "marwa123_"; // Change this to a secure key
    private static $issuer = "hospital_management"; // Change this to your issuer

    public static function generateToken($data, $expiry)
    {
        $token = [
            "iss" => self::$issuer,
            "aud" => self::$issuer,
            "iat" => time(),
            "exp" => $expiry,
            "data" => $data,
        ];

        return JWT::encode($token, self::$key);
    }

    public static function validateToken($token)
    {
        try {
            $decoded = JWT::decode($token, self::$key, array('HS256'));
            return $decoded->data;
        } catch (\Exception $e) {
            return null;
        }
    }
}
