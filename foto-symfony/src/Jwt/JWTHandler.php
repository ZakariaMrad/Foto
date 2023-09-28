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
        $this->error = null;
    }

    public function handle($data)
    {
        $token = $data['jwtToken'] ?? null;
        unset($data['jwtToken']);
        if (!$token) {
            $this->error = 'JWT token not provided.';
            return [false, $data, $token];
        }
        try {
            $this->decodedJWTToken = $this->jwtEncoder->decode($token);
        } catch (JWTDecodeFailureException $e) {
            $this->error = 'JWT token not decodable.';
            return [false, $data, $token];
        }
        if (!$this->decodedJWTToken) {
            $this->error = 'Invalid JWT token.';
            return [false, $data, $token];
        }
        if ($this->decodedJWTToken['exp'] < (new \DateTime())->getTimestamp()) {
            $this->error = 'JWT token expired.';
            return [false, $data, $token];
        }
        if ($this->decodedJWTToken['exp'] < (new \DateTime())->getTimestamp() + $_ENV['JWT_REFRESH_DELAY']) {
            $token = $this->create($this->decodedJWTToken['email'], $this->decodedJWTToken['idUser']);
        }
        return [true, $data, $token];
    }

    public function create(string $email, int $idUser)
    {
        $payload = [
            'email' => $email,
            'exp' => (new \DateTime())->getTimestamp() + $_ENV['JWT_TTL'],
            'idUser'=>$idUser
        ];
        return $this->jwtEncoder->encode($payload);
    }
}
