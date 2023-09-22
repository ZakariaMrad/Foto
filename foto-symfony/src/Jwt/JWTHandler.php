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

    public function handle($data): bool|string
    {
        $token = $data['jwtToken'] ?? null;
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
        if($this->decodedJWTToken['exp'] < (new \DateTime())->getTimestamp()){
            $this->error = 'JWT token expired.';
            return true;
        }
        if($this->decodedJWTToken['exp'] < (new \DateTime())->getTimestamp()+$_ENV['JWT_REFRESH_DELAY']){
            return $this->create($this->decodedJWTToken['email']);
        }
        return false;
    }

    public function create(string $email)
    {
        $payload = [
            'email' => $email,
            'exp' => (new \DateTime())->getTimestamp()+$_ENV['JWT_TTL'],
        ];
        return $this->jwtEncoder->encode($payload);
    }
}
