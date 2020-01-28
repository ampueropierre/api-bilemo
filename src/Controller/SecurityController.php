<?php

namespace App\Controller;

use App\Form\UserType;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use App\Entity\User;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractFOSRestController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Create a new Client
     * @Rest\Post("api/register", name="app_user_register")
     * @param Request $request
     * @View(StatusCode=201,serializerGroups={"public"})
     * @SWG\Response(
     *     response="201",
     *     description="Created a new User"
     * )
     * @SWG\Response(
     *     response="400",
     *     description="Not validation"
     * )
     * @SWG\Tag(name="Register")
     */
    public function register(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($this->passwordEncoder->encodePassword($user,$user->getPassword()));
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $user;
        }

        return $this->view($form->getErrors(), Response::HTTP_BAD_REQUEST);
    }
}
