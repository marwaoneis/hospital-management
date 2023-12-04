<?php

class AuthMiddleware
{
    public static function authenticate()
    {
        $headers = getallheaders();

        if (isset($headers['Authorization'])) {
            $token = str_replace('Bearer ', '', $headers['Authorization']);

            $userData = JWTUtil::validateToken($token);

            if ($userData !== null) {
                return $userData;
            }
        }

        http_response_code(401);
        die(json_encode(array("message" => "Unauthorized")));
    }
}
