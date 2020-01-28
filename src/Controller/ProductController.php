<?php

namespace App\Controller;

use App\Entity\Product;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\Validator\ConstraintViolationList;
use App\Exception\ResourceValidationException;

class ProductController extends AbstractFOSRestController
{

    /**
     * Returns a collection of Product
     * @Rest\Get("api/products", name="app_product_list")
     * @View(statusCode=200, serializerGroups={"product"})
     * @SWG\Response(
     *     response="200",
     *     description="Success"
     * )
     * @SWG\Response(
     *     response="401",
     *     description="Unauthorized"
     * )
     * @SWG\Tag(name="Product")
     * @Security(name="Bearer")
     */
    public function getProducts()
    {
        return $this->getDoctrine()->getRepository(Product::class)->findAll();
    }

    /**
     * Return one Product
     * @Rest\Get("api/product/{id}", name="app_product_show", requirements={"id"="\d+"})
     * @param Product $product
     * @View(statusCode=200, serializerGroups={"product"})
     * @SWG\Response(
     *     response="200",
     *     description="Success"
     * )
     * @SWG\Response(
     *     response="401",
     *     description="Unauthorized"
     * )
     * @SWG\Response(
     *     response="404",
     *     description="Product not found"
     * )
     * @SWG\Tag(name="Product")
     */
    public function getProduct(Product $product)
    {
        return $product;
    }

    /**
     * Create a Product
     * @Rest\Post("api/product", name="app_product_create")
     * @param Product $product
     * @ParamConverter("product", converter="fos_rest.request_body")
     * @param ConstraintViolationList $violations
     * @Rest\View(statusCode=201, serializerGroups={"product"})
     * @IsGranted("ROLE_ADMIN")
     * @SWG\Response(
     *     response="201",
     *     description="Created"
     * )
     * @SWG\Response(
     *     response="401",
     *     description="Unauthorized"
     * )
     * @SWG\Response(
     *     response="400",
     *     description="Invalid data"
     * )
     * @SWG\Tag(name="Product")
     */
    public function createProduct(Product $product,ConstraintViolationList $violations)
    {
        if (count($violations)) {
            $message = 'The JSON sent contains invalid data. Here are the errors you need to correct: ';
            foreach ($violations as $violation) {
                $message .= sprintf("Field %s: %s ", $violation->getPropertyPath(), $violation->getMessage());
            }

            throw new ResourceValidationException($message);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();

        return $product;
    }
}
