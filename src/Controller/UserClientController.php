<?php

namespace App\Controller;

use App\Entity\UserClient;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Validator\ConstraintViolationList;

class UserClientController extends AbstractFOSRestController
{
    /**
     * @Rest\Get("api/users", name="app_user_list")
     * @View(serializerGroups={"list"})
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
     * @Rest\Get("api/user/{id}", name="app_user_show", requirements={"id"="\d+"})
     * @param UserClient $userClient
     * @View(serializerGroups={"show"})
     */
    public function showUser(UserClient $userClient)
    {
        if ($userClient->getUser() != $this->getUser()) {
            return $this->view(['message' => 'Vous n\'etes pas le createur de cette utilisateur'], Response::HTTP_BAD_REQUEST);
        }

        return $userClient;
    }

    /**
     * @Rest\Post("api/user", name="app_user_create")
     * @param UserClient $userClient
     * @param ConstraintViolationList $violations
     * @View(statusCode=201,serializerGroups={"show"})
     * @ParamConverter(
     *     "userClient",
     *     converter="fos_rest.request_body",
     *     options={
     *          "validator"={ "groups"="Create" }
     *     }
     * )
     */
    public function createUser(UserClient $userClient, ConstraintViolationList $violations)
    {
        if (count($violations)) {
            return $this->view($violations, Response::HTTP_BAD_REQUEST);
        }

        $userClient->setUser($this->getUser());

        $em = $this->getDoctrine()->getManager();
        $em->persist($userClient);
        $em->flush();

        return $this->view(
            $userClient,
            201,
            ['Location' => $this->generateUrl('app_user_show', ['id' => $userClient->getId(), UrlGeneratorInterface::ABSOLUTE_URL])]
        );
    }

    /**
     * @Rest\Delete("api/user/{id}", name="app_user_delete", requirements={"id"="\d+"})
     * @param UserClient $user
     * @View(statusCode=204)
     */
    public function deleteUser(UserClient $user)
    {
        if ($user->getUser() != $this->getUser()) {
            return $this->view(['message' => 'Impossible'], Response::HTTP_CONFLICT);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return $this->view([], 204);
    }

    /**
     * @Rest\Put("api/user/{id}", name="app_user_update", requirements={"id"="\d+"})
     * @param UserClient $user
     * @param UserClient $userUpdate
     * @param ConstraintViolationList $violations
     * @View(statusCode=200, serializerGroups={"show"})
     * @ParamConverter("user")
     * @ParamConverter(
     *     "userUpdate",
     *     converter="fos_rest.request_body",
     *     options={
     *          "validator"={ "groups"="Update" }
     *     }
     * )
     */
    public function updateUser(UserClient $user, UserClient $userUpdate, ConstraintViolationList $violations)
    {
        if ($user->getUser() != $this->getUser()) {
            return $this->view(['message' => 'Vous n\'etes pas le createur de cette utilisateur'], Response::HTTP_BAD_REQUEST);
        }

        if (count($violations)) {
            return $this->view($violations, Response::HTTP_BAD_REQUEST);
        }

        $user->setFullName($userUpdate->getFullName());
        $user->setEmail($userUpdate->getEmail());

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $this->view(
            $user,
            200,
            ['Location' => $this->generateUrl('app_user_show', ['id' => $user->getId(), UrlGeneratorInterface::ABSOLUTE_URL])]
        );
    }
}
