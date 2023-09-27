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
        if (!$token) {
            $this->error = 'Jeton JWT manquant.';
            return [false, $data];
        }
        try {
            $this->decodedJWTToken = $this->jwtEncoder->decode($token);
        } catch (JWTDecodeFailureException $e) {
            $this->error = 'Impossible de décoder le jeton JWT.';
            return [false, $data];
        }
        if (!$this->decodedJWTToken) {
            $this->error = 'Jeton JWT invalide.';
            return [false, $data];
        }
        if ($this->decodedJWTToken['exp'] < (new \DateTime())->getTimestamp()) {
            $this->error = 'Jeton JWT expiré.';
            return [false, $data];
        }
        if ($this->decodedJWTToken['exp'] < (new \DateTime())->getTimestamp() + $_ENV['JWT_REFRESH_DELAY']) {
            $data['jwtToken'] = $this->create($this->decodedJWTToken['email']);
        }
        return [true, $data];
    }

    public function create(string $email)
    {
        $payload = [
            'email' => $email,
            'exp' => (new \DateTime())->getTimestamp() + $_ENV['JWT_TTL'],
        ];
        return $this->jwtEncoder->encode($payload);
    }
}
