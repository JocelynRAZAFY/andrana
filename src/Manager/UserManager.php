<?php
/**
 * Created by PhpStorm.
 * User: jocelyn
 * Date: 5/1/19
 * Time: 10:58 AM
 */

namespace App\Manager;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;

class UserManager extends BaseManager
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var JWTTokenManagerInterface
     */
    private $tokenManager;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * UserManager constructor.
     * @param EntityManagerInterface $em
     * @param ContainerInterface $container
     * @param RequestStack $requestStack
     * @param SessionInterface $session
     * @param LoggerInterface $logger
     * @param SerializerInterface $serializer
     * @param UserRepository $userRepository
     * @param JWTTokenManagerInterface $tokenManager
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(
        EntityManagerInterface $em,
        ContainerInterface $container,
        RequestStack $requestStack,
        SessionInterface $session,
        LoggerInterface $logger,
        SerializerInterface $serializer,
        UserRepository $userRepository,
        JWTTokenManagerInterface $tokenManager,
        UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->userRepository = $userRepository;
        $this->tokenManager = $tokenManager;
        $this->passwordEncoder = $passwordEncoder;
        parent::__construct($em, $container, $requestStack, $session, $logger, $serializer);
    }

    /**
     * @return JsonResponse
     */
    public function login()
    {
        $user = $this->userRepository->findOneBy(['email' => $this->data->email]);
        if(!$user){
            return $this->unauthorized('Email ou mot de passe incorrect');
        }
         if(!$this->passwordEncoder->isPasswordValid($user,$this->data->password)){
             return $this->unauthorized('Email ou mot de passe incorrect');
         }
         $token = $this->tokenManager->create($user);

        return new JsonResponse(['token' => $token,'userId' => $user->getId()],200);
    }

    /**
     * @return JsonResponse
     */
    public function logout()
    {
        return $this->success(['logout' => true]);
    }

    /**
     * @return JsonResponse
     */
    public function userUpdate()
    {
        return $this->success(['res' => true]);
    }

    /**
     * @return JsonResponse
     */
    public function userInfo()
    {
        return $this->success(['userInfo' => true]);
    }
}
