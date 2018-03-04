<?php

namespace Decarte\Shop\Controller\Product;

use Decarte\Shop\Repository\Product\ProductCollectionRepository;
use Decarte\Shop\Repository\Product\ProductRepository;
use Decarte\Shop\Repository\Product\ProductTypeRepository;
use Decarte\Shop\Service\Schema\BreadcrumbListSchema;
use Decarte\Shop\Service\Schema\ProductSchema;
use Decarte\Shop\Service\Url\ProductUrl;
use Decarte\Shop\Service\View\Breadcrumb\Product\ProductBreadcrumbs;
use Decarte\Shop\Service\View\Breadcrumb\Product\ProductCollectionBreadcrumbs;
use Decarte\Shop\Service\View\Breadcrumb\Product\ProductTypeBreadcrumbs;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ProductsController extends Controller
{
    /**
     * @Route("/sklep/{type}", name="shop_list_collections", requirements={"type": "[0-9a-z\-]+"})
     * @param string $type
     * @return Response
     */
    public function listCollectionsAction(
        string $type,
        ProductTypeRepository $productTypeRepository,
        ProductCollectionRepository $productCollectionRepository,
        ProductTypeBreadcrumbs $breadcrumbsGenerator,
        BreadcrumbListSchema $breadcrumbsSchema
    ): Response {
        $productType = $productTypeRepository->findBySlugName($type);
        if (!$productType) {
            throw $this->createNotFoundException('Nie znaleziono produktów tego typu');
        }

        $productCollections = $productCollectionRepository->getProductCollections($productType->getId());
        if (!$productCollections) {
            throw $this->createNotFoundException('Nie znaleziono produktów tego typu');
        }

        $breadcrumbs = $breadcrumbsGenerator->generate($productType);

        return $this->render('shop/list-collections.html.twig', [
            'productType' => $productType,
            'productCollections' => $productCollections,
            'breadcrumbs' => $breadcrumbs,
            'breadcrumbsSchema' => $breadcrumbsSchema->generateData($breadcrumbs),
        ]);
    }

    /**
     * @Route(
     *     "/sklep/{type}/{slugName}",
     *     name="shop_view_collection",
     *     requirements={"type": "[0-9a-z\-]+", "slugName": "[a-z0-9\-]+"}
     * )
     * @param string $type
     * @param string $slugName
     * @return Response
     */
    public function viewCollectionAction(
        string $type,
        string $slugName,
        ProductCollectionRepository $productCollectionRepository,
        ProductCollectionBreadcrumbs $breadcrumbsGenerator,
        BreadcrumbListSchema $breadcrumbsSchema
    ): Response {
        $productCollection = $productCollectionRepository->findBySlugName($type, $slugName);
        if (!$productCollection) {
            throw $this->createNotFoundException('Nie znaleziono kolekcji produktów');
        }

        $productType = $productCollection->getProductType();
        $allCollections = $productCollectionRepository->getProductCollections($productType->getId());
        $breadcrumbs = $breadcrumbsGenerator->generate($productCollection);

        return $this->render('shop/view-collection.html.twig', [
            'productCollection' => $productCollection,
            'allCollections' => $allCollections,
            'breadcrumbs' => $breadcrumbs,
            'breadcrumbsSchema' => $breadcrumbsSchema->generateData($breadcrumbs),
        ]);
    }

    /**
     * @Route(
     *     "/sklep/{type}/{slugName}/{id}",
     *     name="shop_view_product",
     *     requirements={"type": "[0-9a-z\-]+", "slugName": "[a-z0-9\-]+", "id": "\d+"}
     * )
     * @param string $type Used only for SEO.
     * @param string $slugName Used only for SEO.
     * @param int $id
     * @return Response
     */
    public function viewProductAction(
        string $type,
        string $slugName,
        int $id,
        ProductRepository $productRepository,
        ProductUrl $productUrl,
        ProductSchema $productSchema,
        ProductBreadcrumbs $breadcrumbsGenerator,
        BreadcrumbListSchema $breadcrumbsSchema
    ): Response {
        $product = $productRepository->find($id);
        if (!$product || !$product->isVisible()) {
            throw $this->createNotFoundException('Nie znaleziono produktu');
        }

        $previousProduct = $productRepository->findPrevious($product);
        $nextProduct = $productRepository->findNext($product);
        $nextPath = null;

        $previousPath = $previousProduct ? $productUrl->generate($previousProduct) : null;
        $nextPath = $nextProduct ? $productUrl->generate($nextProduct) : null;
        $breadcrumbs = $breadcrumbsGenerator->generate($product);

        return $this->render('shop/view-product.html.twig', [
            'product' => $product,
            'schema' => $productSchema->generateProductData($product),
            'breadcrumbs' => $breadcrumbs,
            'breadcrumbsSchema' => $breadcrumbsSchema->generateData($breadcrumbs),
            'previousPath' => $previousPath,
            'nextPath' => $nextPath,
            'previousUrl' => $previousPath ? $this->getParameter('canonical_domain') . $previousPath : null,
            'nextUrl' => $nextPath ? $this->getParameter('canonical_domain') . $nextPath : null,
        ]);
    }
}
