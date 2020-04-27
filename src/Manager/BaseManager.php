<?php
/**
 * Created by PhpStorm.
 * User: jocelyn
 * Date: 3/3/19
 * Time: 1:49 PM
 */

namespace App\Manager;

use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Serializer\SerializerInterface;

abstract class BaseManager
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var RequestStack
     */
    public $request;

    /**
     * @var mixed
     */
    public $data;

    /**
     * @var mixed
     */
    public $dataArray;

    /**
     * @var array
     */
    public $formData;

    /**
     * @var SessionInterface
     */
    public $session;

    /**
     * @var array
     */
    public $files;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var SerializerInterface
     */
    public $serializer;

    /**
     * BaseManager constructor.
     * @param EntityManagerInterface $em
     * @param ContainerInterface $container
     * @param RequestStack $requestStack
     * @param SerializerInterface $serializer
     * @param LoggerInterface $logger
     */
    public function __construct(
        EntityManagerInterface $em,
        ContainerInterface $container,
        RequestStack $requestStack,
        SessionInterface $session,
        LoggerInterface $logger,
        SerializerInterface $serializer)
    {
        $this->em = $em;
        $this->container = $container;
        $this->request = $requestStack->getCurrentRequest();
        $this->data = $this->request != null ? json_decode($this->request->getContent()) : null;
        $this->dataArray = $this->request != null ? json_decode($this->request->getContent(),true) : null;
        $this->formData = $this->request->request->all();
        $this->session = $session;
        $this->files = $this->request->files->all();
        $this->logger = $logger;
        $this->serializer = $serializer;
    }

    /**
     * Save the entity
     *
     * @param $entity
     */
    public function save(&$entity)
    {
        $this->em->persist($entity);
        $this->em->flush();
    }

    /**
     * remove the entity
     *
     * @param $entity
     */
    public function remove($entity)
    {
        $this->em->remove($entity);
        $this->em->flush();
    }

    /**
     * Creates and returns a Form instance from the type of the form.
     *
     * @final
     */
    protected function createForm(string $type, $data = null, array $options = array()): FormInterface
    {
        return $this->container->get('form.factory')->create($type, $data, $options);
    }


    /**
     * Get a user from the Security Token Storage.
     *
     * @return mixed
     *
     * @throws \LogicException If SecurityBundle is not available
     *
     * @see TokenInterface::getUser()
     *
     * @final
     */
    protected function getUser()
    {
        if (!$this->container->has('security.token_storage')) {
            throw new \LogicException('The SecurityBundle is not registered in your application. Try running "composer require symfony/security-bundle".');
        }

        if (null === $token = $this->container->get('security.token_storage')->getToken()) {
            return;
        }

        if (!is_object($user = $token->getUser())) {
            // e.g. anonymous authentication
            return;
        }

        return $user;
    }

    /**
     * return result to json
     *
     * @param $data
     * @return JsonResponse
     */
    public function success($data = null,?string $key = null,?string $headers= null)
    {
        $response = new JsonResponse(['code' => 200, 'success' => true, 'data' => $data], 200);
        if($headers != null){
            $response->headers->set($key,$headers);
        }
//        return new JsonResponse(['code' => 200, 'success' => true, 'data' => $data], 200);
        return $response;
    }

    /**
     * return result to json
     *
     * @param $data
     * @return JsonResponse
     */
    public function failure($data = null)
    {
        return new JsonResponse(['code' => 200, 'success' => false, 'data' => $data], 200);
    }

    /**
     * return JSON error message
     *
     * @param $message
     * @return JsonResponse
     */
    public function error($message)
    {
        return new JsonResponse(['code' => 500, 'success' => false, 'message' => $message], 500);
    }

    /**
     * @param $message
     * @return JsonResponse
     */
    public function unauthorized($message)
    {
        return new JsonResponse(['code' => 401, 'success' => false, 'message' => $message], 401);
    }

    /**
     * @param $message
     * @return JsonResponse
     */
    public function badRequest($message)
    {
        return new JsonResponse(['code' => 400, 'success' => false, 'message' => $message], 400);
    }

    /**
     * @param $message
     * @return JsonResponse
     */
    public function notFound($message)
    {
        return new JsonResponse(['code' => 404, 'success' => false, 'message' => $message], 404);
    }

    /**
     * Manage cookie
     *
     * @param $key
     * @param $value
     * @param $timeOut
     * @return bool
     */
    public function setCookie($key, $value, $timeOut)
    {
        setcookie($key, $value, $timeOut, "/");

        return true ;
    }

    /**
     * Verify if cookie have this key
     *
     * @param type $_key
     * @return type
     */
    public function hasCookieKey($key)
    {
        return $this->request->cookies->has($key) ;
    }

    /**
     * Get cookie value by key
     *
     * @param type $_key
     * @return string
     */
    public function getCookie($key)
    {
        $value = $this->request->cookies->get($key)  ;

        return $value ;
    }

    /**
     *  Get parameter in service.yaml
     *
     * @param $name
     * @return mixed
     */
    public function getParameter($name)
    {
        return $this->container->getParameter($name);
    }
}