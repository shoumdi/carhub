<?php

namespace JwtAuth;

class Base64UrlEncoder
{

    public function encode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '/-', '+_'), '=');
    }

    public function decode($data): string
    {
        $data = base64_decode(strtr($data, '+_', '/-'));
        while (strlen($data) % 4) $data .= '=';
        return $data;
    }
}
