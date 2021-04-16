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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class UserManager extends BaseManager
{
    private UserRepository $userRepository;

    private JWTTokenManagerInterface $tokenManager;

    private UserPasswordEncoderInterface $passwordEncoder;

    private NormalizerInterface $normalizer;

    private Security $security;

    public function __construct(EntityManagerInterface $em,
                                ContainerInterface $container,
                                RequestStack $request,
                                Security $security,
                                NormalizerInterface $normalizer,
                                UserRepository $userRepository,
                                JWTTokenManagerInterface $tokenManager,
                                UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->userRepository = $userRepository;
        $this->tokenManager = $tokenManager;
        $this->passwordEncoder = $passwordEncoder;
        parent::__construct($em, $container, $request, $security, $normalizer);
    }

    public function login(): JsonResponse
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

    public function logout(): JsonResponse
    {
        return $this->success(['logout' => true]);
    }

    public function userUpdate(): JsonResponse
    {
        return $this->success(['res' => true]);
    }

    public function userInfo(): JsonResponse
    {
        $user = $this->security->getUser();
        return $this->success(['user' => $this->normalize($user,['groups' => ['list_user']])]);
    }
}
