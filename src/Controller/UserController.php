<?php

namespace App\Controller;

use App\Entity\User;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Validator\ConstraintViolationList;

class UserController extends AbstractFOSRestController
{
    /**
     * @Rest\Get("/users", name="app_user_list")
     * @View(serializerGroups={"list"})
     */
    public function getUsers()
    {
        return $this->getDoctrine()->getRepository(User::class)->findAll();
    }

    /**
     * @Rest\Get("/user/{id}", name="app_user_show", requirements={"id"="\d+"})
     * @param User $user
     * @View(serializerGroups={"show"})
     */
    public function showUser(User $user)
    {
        return $user;
    }

    /**
     * @Rest\Post("/user", name="app_user_create")
     * @param User $user
     * @param ConstraintViolationList $violations
     * @View(statusCode=201,serializerGroups={"show"})
     * @ParamConverter(
     *     "user",
     *     converter="fos_rest.request_body",
     *     options={
     *          "validator"={ "groups"="Create" }
     *     }
     * )
     */
    public function createUser(User $user, ConstraintViolationList $violations)
    {
        if (count($violations)) {
            return $this->view($violations, Response::HTTP_BAD_REQUEST);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->view(
            $user,
            201,
            ['Location' => $this->generateUrl('app_user_show', ['id' => $user->getId(), UrlGeneratorInterface::ABSOLUTE_URL])]
        );
    }

    /**
     * @Rest\Delete("user/{id}", name="app_user_delete", requirements={"id"="\d+"})
     * @param User $user
     * @View(statusCode=204)
     */
    public function deleteUser(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return $this->view([], 204);
    }

    /**
     * @Rest\Put("user/{id}", name="app_user_update", requirements={"id"="\d+"})
     * @param User $user
     * @param User $userUpdate
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
    public function updateUser(User $user, User $userUpdate, ConstraintViolationList $violations)
    {
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
