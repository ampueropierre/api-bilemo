<?php

namespace App\Controller;

use App\Entity\Product;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Context\Context;

class ProductController extends AbstractFOSRestController
{

    /**
     * Lists all products
     * @Rest\Get("/products", name="app_product_list")
     * @return Response
     */
    public function getProducts()
    {
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();
        $context = new Context();
        $context->addGroup('product');
        $view = $this->view($products, 200);
        $view->setContext($context);

        return $this->handleView($view);
    }

    /**
     * Show one product
     * @Rest\Get("/product/{id}", name="app_product_show", requirements={"id"="\d+"})
     * @param Product $product
     * @return Response
     */
    public function getProduct(Product $product)
    {
        $context = new Context();
        $context->addGroup('product');
        $view = $this->view($product,200);
        $view->setContext($context);

        return $this->handleView($view);
    }
}
