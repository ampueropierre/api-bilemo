<?php

namespace App\Controller;

use App\Entity\UserClient;
use App\Exception\ResourceValidationException;
use App\Exception\NotUserCreatedException;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class UserClientController extends AbstractFOSRestController
{
    /**
     * Return list of UserClient to User
     * @Rest\Get("api/users", name="app_userclient_list")
     * @View(statusCode=200, serializerGroups={"list"})
     * @SWG\Response(
     *     response="200",
     *     description="Success"
     * )
     * @SWG\Response(
     *     response="401",
     *     description="Unauthorized"
     * )
     * @SWG\Tag(name="User of Client")
     * @SWG\Parameter( name="Authorization", in="header", required=true, type="string", default="Bearer TOKEN", description="Authorization" )
     */
    public function getUsers()
    {
        $list = $this->getDoctrine()->getRepository(UserClient::class)->findBy(['user' => $this->getUser()]);
        if (empty($list)) {
            return $this->view(['message' => 'List Empty'], 200);
        }

        return $list;
    }

    /**
     * Return User Client
     * @Rest\Get("api/user/{id}", name="app_user_show", requirements={"id"="\d+"})
     * @param UserClient $userClient
     * @param CacheInterface $cache
     * @View(statusCode=200, serializerGroups={"show"})
     * @SWG\Response(
     *     response="200",
     *     description="Success"
     * )
     * @SWG\Response(
     *     response="400",
     *     description="Not creator"
     * )
     * @SWG\Response(
     *     response="401",
     *     description="Unauthorized"
     * )
     * @SWG\Tag(name="User of Client")
     * @SWG\Parameter( name="Authorization", in="header", required=true, type="string", default="Bearer TOKEN", description="Authorization" )
     */
    public function showUser(UserClient $userClient, CacheInterface $cache)
    {
        if ($userClient->getUser() != $this->getUser()) {
            throw new NotUserCreatedException('Not Creator');
        }

        return $cache->get('user_'.$userClient->getId(), function (ItemInterface $item) use ($userClient) {
            $item->expiresAfter(3600);
            return $userClient;
        });
    }

    /**
     * Create a new User
     * @Rest\Post("api/user", name="app_user_create")
     * @param UserClient $userClient
     * @param ConstraintViolationList $violations
     * @param CacheInterface $cache
     * @View(statusCode=201,serializerGroups={"show"})
     * @ParamConverter(
     *     "userClient",
     *     converter="fos_rest.request_body",
     *     options={
     *          "validator"={ "groups"="Create" }
     *     }
     * )
     * @SWG\Response(
     *     response="201",
     *     description="Created"
     * )
     * @SWG\Response(
     *     response="400",
     *     description="Not validation"
     * )
     * @SWG\Response(
     *     response="401",
     *     description="Unauthorized"
     * )
     * @SWG\Tag(name="User of Client")
     * @SWG\Parameter( name="Authorization", in="header", required=true, type="string", default="Bearer TOKEN", description="Authorization" )
     * @SWG\Parameter( name="full_name", in="formData", type="string", required=true )
     * @SWG\Parameter( name="email", in="formData", type="string", required=true )
     */
    public function createUser(UserClient $userClient, ConstraintViolationList $violations, CacheInterface $cache)
    {
        if (count($violations)) {
            $message = 'The JSON sent contains invalid data. Here are the errors you need to correct: ';
            foreach ($violations as $violation) {
                $message .= sprintf("Field %s: %s ", $violation->getPropertyPath(), $violation->getMessage());
            }

            throw new ResourceValidationException($message);
        }

        $userClient->setUser($this->getUser());

        $em = $this->getDoctrine()->getManager();
        $em->persist($userClient);
        $em->flush();

        $cache->get('user_'.$userClient->getId(), function (ItemInterface $item) use ($userClient) {
           $item->expiresAfter(3600);

           return $userClient;
        });

        return $this->view(
            $userClient,
            201,
            ['Location' => $this->generateUrl('app_user_show', ['id' => $userClient->getId(), UrlGeneratorInterface::ABSOLUTE_URL])]
        );
    }

    /**
     * Delete User
     * @Rest\Delete("api/user/{id}", name="app_user_delete", requirements={"id"="\d+"})
     * @param UserClient $user
     * @View(statusCode=204)
     * @SWG\Response(
     *     response="204",
     *     description="Delete"
     * )
     * @SWG\Response(
     *     response="400",
     *     description="Not creator"
     * )
     * @SWG\Response(
     *     response="401",
     *     description="Unauthorized"
     * )
     * @SWG\Tag(name="User of Client")
     * @SWG\Parameter( name="Authorization", in="header", required=true, type="string", default="Bearer TOKEN", description="Authorization" )
     */
    public function deleteUser(UserClient $user)
    {
        if ($user->getUser() != $this->getUser()) {
            throw new NotUserCreatedException('Not Creator');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return $this->view([], 204);
    }

    /**
     * Edit User
     * @Rest\Put("api/user/{id}", name="app_user_update", requirements={"id"="\d+"})
     * @param UserClient $user
     * @param UserClient $userUpdate
     * @param ConstraintViolationList $violations
     * @param CacheInterface $cache
     * @View(statusCode=200, serializerGroups={"show"})
     * @ParamConverter("userClient")
     * @ParamConverter(
     *     "userUpdate",
     *     converter="fos_rest.request_body",
     *     options={
     *          "validator"={ "groups"="Update" }
     *     }
     * )
     * @SWG\Response(
     *     response="200",
     *     description="Success"
     * )
     * @SWG\Response(
     *     response="400",
     *     description="Not creator or Invalid data"
     * )
     * @SWG\Response(
     *     response="401",
     *     description="Unauthorized"
     * )
     * @SWG\Tag(name="User of Client")
     * @SWG\Parameter( name="Authorization", in="header", required=true, type="string", default="Bearer TOKEN", description="Authorization" )
     */
    public function updateUser(UserClient $user, UserClient $userUpdate, ConstraintViolationList $violations, CacheInterface $cache)
    {
        if ($user->getUser() != $this->getUser()) {
            throw new NotUserCreatedException('Not Creator');
        }

        if (count($violations)) {
            $message = 'The JSON sent contains invalid data. Here are the errors you need to correct: ';
            foreach ($violations as $violation) {
                $message .= sprintf("Field %s: %s ", $violation->getPropertyPath(), $violation->getMessage());
            }

            throw new ResourceValidationException($message);
        }

        $mail = $this->getDoctrine()->getRepository(UserClient::class)->findOneBy(['email' => $userUpdate->getEmail()]);
        if ($mail) {
            throw new ResourceValidationException('Email exist in Database');
        }

        $user->setFullName($userUpdate->getFullName());
        $user->setEmail($userUpdate->getEmail());

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        $cache->delete('user_'.$user->getId());

        return $this->view(
            $user,
            200,
            ['Location' => $this->generateUrl('app_user_show', ['id' => $user->getId(), UrlGeneratorInterface::ABSOLUTE_URL])]
        );
    }
}
