<?php

namespace App\Helpers;

use App\Models\Users;
use Lcobucci\Clock\FrozenClock;
use Lcobucci\JWT\Validation\Constraint\ValidAt;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Lcobucci\JWT\Validation\Constraint\IdentifiedBy;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Validation\RequiredConstraintsViolated;

class JWT
{
    /** @var Token $token description */
    private $token;

    /** @var Configuration $config description */
    private $config;

    /** @var Users $config description */
    private $user;

    /**
     * Prepare signer and key
     *
     * @return JWT
     **/
    public function __construct()
    {
        $this->config = Configuration::forSymmetricSigner(
            new Sha256(),
            InMemory::base64Encoded($_ENV["JWT_KEY"])
        );
        $this->config;

        return $this;
    }

    /**
     * Generate token
     *
     * @param int $uid
     * @param string $jti
     * @return JWT
     **/
    public function create(int $uid, string $jti)
    {
        $now = new \DateTimeImmutable();
        $this->token = $this->config
            ->builder()
            ->issuedBy($_ENV["JWT_ISSUED_BY"])
            ->withClaim('uid', $uid)
            ->identifiedBy($jti)
            ->issuedAt($now)
            ->expiresAt($now->modify("+{$_ENV['JWT_TIMEOUT']} hours"))
            ->getToken($this->config->signer(), $this->config->signingKey());

        return $this;
    }

    /**
     * Generate random key
     *
     * @return string
     **/
    public static function jtiGenerator()
    {
        return bin2hex(openssl_random_pseudo_bytes(8));
    }

    /**
     * Parse string token and store data
     *
     * @param string $token
     * @return JWT
     **/
    public function parse(string $token)
    {
        $this->token = $this->config->parser()->parse($token);

        return $this;
    }

    /**
     * Validate token
     *
     * @return JWT|false
     **/
    public function validate()
    {
        if(!$this->token){
            return false;
        }

        $clock = new FrozenClock(new \DateTimeImmutable());

        $constraints = [
            new IdentifiedBy($this->getUser()->jti),
            new ValidAt($clock),
            new IssuedBy($_ENV["JWT_ISSUED_BY"])
        ];

        try{
            $this->config
                ->validator()
                ->assert($this->token, ...$constraints);
        } catch (RequiredConstraintsViolated $e){
            return false;
        }

        return $this;
    }

    /**
     * Return Token object
     *
     * @return Token
     **/
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Return string token
     *
     * @return string
     **/
    public function toString()
    {
        return $this->token->toString();
    }

    /**
     * Return string token
     *
     * @return Users|false
     **/
    public function getUser()
    {
        if($this->user){
            return $this->user;
        } else if($this->token){
            return $this->user = Users::findFirst($this->getToken()->claims()->get("uid"));
        } else {
            return false;
        }
    }
}