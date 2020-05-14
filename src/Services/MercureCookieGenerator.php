<?php
/**
 * Created by PhpStorm.
 * User: jocelyn
 * Date: 12/16/19
 * Time: 7:41 PM
 */

namespace App\Services;


use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Dotenv\Dotenv;


class MercureCookieGenerator
{
    private $jwtKey;
    private $container;
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $dotenv = new Dotenv();
        $dotenv->loadEnv(__DIR__.'/../../.env');

        $this->jwtKey = $_ENV['MERCURE_JWT_KEY'];
    }

    public function generate(int $idUser)
    {
        $mercure_secret_key = $this->container->getParameter('mercure_secret_key');
        $token = (new Builder())
            // set other appropriate JWT claims, such as an expiration date
            ->withClaim('mercure', ['subscribe' => ["http://localhost/user/$idUser"]]) // could also include the security roles, or anything else
           // ->getToken(new Sha256(), new Signer\Key($this->jwtKey));
            ->getToken(new Sha256(), new Signer\Key());

//        return sprintf('mercureAuthorization=%s; path=/hub; secure; httponly; SameSite=strict', $token);
        return sprintf('mercureAuthorization=%s; path=/hub; httponly', $token);
    }
}