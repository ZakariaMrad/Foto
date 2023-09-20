<?php

namespace App\Jwt;

use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;

class JWTHandler
{
    public ?string $error;
    public array $decodedJWTToken = [];

    private JWTEncoderInterface $jwtEncoder;

    public function __construct(JWTEncoderInterface $jwtEncoder)
    {
        $this->jwtEncoder = $jwtEncoder;
        $this->error=null;
    }

    public function handle($data): bool
    {
        $token = $data['jwt_token'] ?? null;
        if (!$token) {
            $this->error = 'JWT token not provided.';
            return true;
        }
        try {
            $this->decodedJWTToken = $this->jwtEncoder->decode($token);
        } catch (JWTDecodeFailureException $e) {
            $this->error = 'JWT token not decodable.';
            return true;
        }
        if (!$this->decodedJWTToken) {
            $this->error = 'Invalid JWT token.';
            return true;
        }
        return false;
    }

    public function create($user)
    {
        $payload = [
            'email' => $user->getEmail(),
            'exp' => (new \DateTime())->modify('+1 day')->getTimestamp(),
        ];
        return $this->jwtEncoder->encode($payload);
    }
}
