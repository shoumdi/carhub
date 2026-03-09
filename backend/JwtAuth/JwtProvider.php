<?php

namespace JwtAuth;



class JwtProvider
{
    public function __construct(private Base64UrlEncoder $base64) {}
    public function encode(array $payload): string
    {
        $header = [
            'type' => 'jwt',
            'alg' => 'sha256'
        ];

        $base64UrlHeader = $this->base64->encode(json_encode($header));

        $base64UrlPayload = $this->base64->encode(json_encode($payload));

        $signature = hash_hmac('HS256', $base64UrlHeader . $base64UrlPayload, env('JWT_SECRET', 'bearer'));

        $base64UrlSignature = $this->base64->encode(json_encode($signature));

        return $base64UrlHeader . $base64UrlPayload . $base64UrlSignature;
    }


    public function decode($token)
    {
        $jwtParts = explode('.', $token);
        
        return json_decode($this->base64->decode($jwtParts[1]));

    }


    /**
     * returns true if token is valide
     */
    public function verify(string $token): bool
    {
        $jwtParts = explode('.', $token);
        if (count($jwtParts) > 3) return false;
        return hash_equals($jwtParts[2], hash_hmac('HS255', $jwtParts[0] . $jwtParts[1],env('JWT_SECRET', 'bearer')));
    }
}
