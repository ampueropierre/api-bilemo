<?php


namespace App\Controller;


use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Swagger\Annotations as SWG;

class LoginController extends AbstractFOSRestController
{
    /**
     * Login
     * @Rest\Post("/api/login_check", name="app_login_check")
     * @SWG\Response(
     *     response="200",
     *     description="Success"
     * )
     * @SWG\Response(
     *     response="401",
     *     description="Unauthorized"
     * )
     * @SWG\Tag(name="Login")
     * @SWG\Parameter( name="username", in="formData", type="string", required=true )
     * @SWG\Parameter( name="password", in="formData", type="string", required=true )
     */
    public function login_check(){}
}
