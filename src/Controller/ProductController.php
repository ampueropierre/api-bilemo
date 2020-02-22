<?php

namespace App\Controller;

use App\Entity\Product;
use App\Representation\Products;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Knp\Component\Pager\PaginatorInterface;
use Swagger\Annotations as SWG;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class ProductController extends AbstractFOSRestController
{

    /**
     * Returns a collection of Product
     * @Rest\Get("api/products", name="app_product_list")
     * @param ParamFetcherInterface $paramFetcher
     * @param PaginatorInterface $paginator
     * @Rest\QueryParam(
     *     name="order",
     *     requirements="asc|desc",
     *     nullable=true,
     *     description="Sort order (asc or desc)"
     * )
     * @Rest\QueryParam(
     *     name="limit",
     *     requirements="\d+",
     *     default="10",
     *     description="Max number of products per page"
     * )
     * @Rest\QueryParam(
     *     name="page",
     *     requirements="\d+",
     *     default="1",
     *     description="Page"
     * )
     * @View(statusCode=200)
     * @SWG\Response(
     *     response="200",
     *     description="Success"
     * )
     * @SWG\Response(
     *     response="401",
     *     description="Unauthorized"
     * )
     * @SWG\Tag(name="Product")
     * @SWG\Parameter( name="Authorization", in="header", required=true, type="string", default="Bearer TOKEN", description="Authorization" )
     */
    public function getProducts(ParamFetcherInterface $paramFetcher,PaginatorInterface $paginator)
    {
        $query = $this->getDoctrine()->getRepository(Product::class)->pagination(
            $this->getUser()->getId(),
            $paramFetcher->get('order')
        );

        $products = $paginator->paginate(
            $query,
            $paramFetcher->get('page'),
            $paramFetcher->get('limit')

        );
        
        return new Products($products);
    }

    /**
     * Return one Product
     * @Rest\Get("api/product/{id}", name="app_product_show", requirements={"id"="\d+"})
     * @param Product $product
     * @param CacheInterface $cache
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
     * @SWG\Parameter( name="Authorization", in="header", required=true, type="string", default="Bearer TOKEN", description="Authorization" )
     */
    public function getProduct(Product $product, CacheInterface $cache)
    {
        return $cache->get('product_'.$product->getId() ,function (ItemInterface $item) use ($product) {
            $item->expiresAfter(3600);
            return $product;
        });
    }
}
