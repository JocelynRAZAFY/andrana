<?php
/**
 * Created by PhpStorm.
 * User: jocelyn
 * Date: 3/3/19
 * Time: 1:49 PM
 */

namespace App\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;


abstract class BaseManager
{
    private EntityManagerInterface $em;

    private ContainerInterface $container;

    private $request;

    public $data;

    public $dataJson;

    public $dataArray;

    public array $formData;

    public array $files;

    private Security $security;

    private NormalizerInterface $normalizer;


    public function __construct(
        EntityManagerInterface $em,
        ContainerInterface $container,
        RequestStack $request,
        Security $security,
        NormalizerInterface $normalizer)
    {
        $this->em = $em;
        $this->container = $container;
        $this->request = $request->getCurrentRequest();
        $this->dataJson = $this->request->getContent();
        $this->data = $this->request != null ? json_decode($this->request->getContent()) : null;
        $this->dataArray = $this->request != null ? json_decode($this->request->getContent(),true) : null;
        $this->formData = $this->request->request->all();
        $this->files = $this->request->files->all();
        $this->security = $security;
        $this->normalizer = $normalizer;

    }

    protected function json($data, int $status = 200, array $headers = [], array $context = []): JsonResponse
    {
        if ($this->container->has('serializer')) {
            $json = $this->container->get('serializer')->serialize($data, 'json', array_merge([
                'json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS,
            ], $context));

            return new JsonResponse($json, $status, $headers, true);
        }

        return new JsonResponse($data, $status, $headers);
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

    protected function getUser()
    {
        return $this->security->getUser();
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
     *  Get parameter in service.yaml
     *
     * @param $name
     * @return mixed
     */
    public function getParameter($name)
    {
        return $this->container->getParameter($name);
    }

    public function normalize($object,$context)
    {
        return $this->normalizer->normalize($object, null,$context);
    }
}
