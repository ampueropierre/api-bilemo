<?php

namespace App\Controller;

use App\Entity\Product;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ProductController extends AbstractFOSRestController
{

    /**
     * Lists all products
     * @Rest\Get("api/products", name="app_product_list")
     * @View(statusCode=200, serializerGroups={"product"})
     */
    public function getProducts()
    {
        return $this->getDoctrine()->getRepository(Product::class)->findAll();
    }

    /**
     * Show one product
     * @Rest\Get("api/product/{id}", name="app_product_show", requirements={"id"="\d+"})
     * @param Product $product
     * @View(statusCode=200, serializerGroups={"product"})
     */
    public function getProduct(Product $product)
    {
        return $product;
    }

    /**
     * @Rest\Post("api/product", name="app_product_create")
     * @param Product $product
     * @ParamConverter("product", converter="fos_rest.request_body")
     * @Rest\View(serializerGroups={"product"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function createProduct(Product $product)
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();

        return $product;
    }
}
