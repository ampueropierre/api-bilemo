<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\View;
use Swagger\Annotations as SWG;

class ClientController extends AbstractFOSRestController
{
    /**
     * Return Information of Client
     * @Rest\Get("api/client/me", name="app_show_client")
     * @View(statusCode=200,serializerGroups={"public"})
     * @SWG\Response(
     *     response="200",
     *     description="Success"
     * )
     * @SWG\Response(
     *     response="401",
     *     description="Unauthorized"
     * )
     * @SWG\Tag(name="Client")
     */
    public function show()
    {
        return $this->getUser();
    }
}
