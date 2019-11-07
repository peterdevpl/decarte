<?php

declare(strict_types=1);

namespace Decarte\Shop\Controller\Product;

use Decarte\Shop\Entity\Product\Product;
use Decarte\Shop\Repository\Order\SessionOrderRepository;
use Decarte\Shop\Repository\Product\ProductCollectionRepository;
use Decarte\Shop\Repository\Product\ProductRepository;
use Decarte\Shop\Repository\Product\ProductTypeRepository;
use Decarte\Shop\Service\Schema\BreadcrumbListSchema;
use Decarte\Shop\Service\Schema\ProductSchema;
use Decarte\Shop\Service\Url\ProductCollectionUrl;
use Decarte\Shop\Service\Url\ProductTypeUrl;
use Decarte\Shop\Service\Url\ProductUrl;
use Decarte\Shop\Service\View\Breadcrumb\Product\ProductBreadcrumbs;
use Decarte\Shop\Service\View\Breadcrumb\Product\ProductCollectionBreadcrumbs;
use Decarte\Shop\Service\View\Breadcrumb\Product\ProductTypeBreadcrumbs;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ProductsController extends AbstractController
{
    /**
     * @Route("/sklep/{type}", name="shop_list_collections", requirements={"type": "[0-9a-z\-]+"})
     *
     * @param string $type
     *
     * @return Response
     */
    public function listCollectionsAction(
        Request $request,
        string $type,
        ProductTypeRepository $productTypeRepository,
        ProductCollectionRepository $productCollectionRepository,
        ProductRepository $productRepository,
        ProductTypeBreadcrumbs $breadcrumbsGenerator,
        BreadcrumbListSchema $breadcrumbsSchema,
        ProductTypeUrl $productTypeUrl,
        ProductUrl $productUrl
    ): Response {
        if ($request->query->has('z')) {
            /** @var ?Product $product */
            $product = $productRepository->find($request->query->get('z'));
            if (!$product) {
                throw $this->createNotFoundException('Nie znaleziono produktu');
            }

            return $this->redirect($productUrl->generate($product), 301);
        }

        $productType = $productTypeRepository->findBySlugName($type);
        if (!$productType) {
            throw $this->createNotFoundException('Nie znaleziono produktów tego typu');
        }

        $productCollections = $productCollectionRepository->getProductCollections($productType->getId());
        if (!$productCollections) {
            throw $this->createNotFoundException('Nie znaleziono produktów tego typu');
        }

        $breadcrumbs = $breadcrumbsGenerator->generate($productType);
        $currentUrl = $productTypeUrl->generate($productType);

        return $this->render('shop/list-collections.html.twig', [
            'productType' => $productType,
            'productCollections' => $productCollections,
            'breadcrumbs' => $breadcrumbs,
            'breadcrumbsSchema' => $breadcrumbsSchema->generateData($breadcrumbs),
            'currentUrl' => $this->getParameter('canonical_domain') . $currentUrl,
        ]);
    }

    /**
     * @Route(
     *     "/sklep/{type}/{slugName}",
     *     name="shop_view_collection",
     *     requirements={"type": "[0-9a-z\-]+", "slugName": "[a-z0-9\-]+"}
     * )
     *
     * @param string $type
     * @param string $slugName
     *
     * @return Response
     */
    public function viewCollectionAction(
        string $type,
        string $slugName,
        ProductCollectionRepository $productCollectionRepository,
        ProductCollectionBreadcrumbs $breadcrumbsGenerator,
        ProductCollectionUrl $productCollectionUrl,
        BreadcrumbListSchema $breadcrumbsSchema
    ): Response {
        $productCollection = $productCollectionRepository->findBySlugName($type, $slugName);
        if (!$productCollection) {
            throw $this->createNotFoundException('Nie znaleziono kolekcji produktów');
        }

        $productType = $productCollection->getProductType();
        $allCollections = $productCollectionRepository->getProductCollections($productType->getId());
        $breadcrumbs = $breadcrumbsGenerator->generate($productCollection);
        $currentUrl = $productCollectionUrl->generate($productCollection);

        return $this->render('shop/view-collection.html.twig', [
            'productCollection' => $productCollection,
            'allCollections' => $allCollections,
            'breadcrumbs' => $breadcrumbs,
            'breadcrumbsSchema' => $breadcrumbsSchema->generateData($breadcrumbs),
            'currentUrl' => $this->getParameter('canonical_domain') . $currentUrl,
        ]);
    }

    /**
     * @Route(
     *     "/sklep/{type}/{slugName}/{id}",
     *     name="shop_view_product",
     *     requirements={"type": "[0-9a-z\-]+", "slugName": "[a-z0-9\-]+", "id": "\d+"}
     * )
     *
     * @param string $type     used only for SEO
     * @param string $slugName used only for SEO
     * @param int    $id
     *
     * @return Response
     */
    public function viewProductAction(
        Request $request,
        string $type,
        string $slugName,
        int $id,
        ProductRepository $productRepository,
        ProductUrl $productUrl,
        ProductSchema $productSchema,
        ProductBreadcrumbs $breadcrumbsGenerator,
        BreadcrumbListSchema $breadcrumbsSchema,
        SessionOrderRepository $samplesOrderRepository
    ): Response {
        if ($request->query->has('z')) {
            /** @var ?Product $product */
            $product = $productRepository->find($request->query->get('z'));
            if (!$product) {
                throw $this->createNotFoundException('Nie znaleziono produktu');
            }

            return $this->redirect($productUrl->generate($product), 301);
        }

        /** @var ?Product $product */
        $product = $productRepository->find($id);
        if (!$product || !$product->isVisible()) {
            throw $this->createNotFoundException('Nie znaleziono produktu');
        }

        $previousProduct = $productRepository->findPrevious($product);
        $nextProduct = $productRepository->findNext($product);
        $nextPath = null;

        $currentPath = $productUrl->generate($product);
        $previousPath = $previousProduct ? $productUrl->generate($previousProduct) : null;
        $nextPath = $nextProduct ? $productUrl->generate($nextProduct) : null;
        $breadcrumbs = $breadcrumbsGenerator->generate($product);

        $samplesOrder = $samplesOrderRepository->getOrder(SessionOrderRepository::SAMPLES);
        $hasDemo = $product->hasDemo() && ($samplesOrder->getItems()->count() < $this->getParameter('samples_count'));

        return $this->render('shop/view-product.html.twig', [
            'product' => $product,
            'schema' => $productSchema->generateProductData($product),
            'breadcrumbs' => $breadcrumbs,
            'breadcrumbsSchema' => $breadcrumbsSchema->generateData($breadcrumbs),
            'previousPath' => $previousPath,
            'nextPath' => $nextPath,
            'currentUrl' => $this->getParameter('canonical_domain') . $currentPath,
            'previousUrl' => $previousPath ? $this->getParameter('canonical_domain') . $previousPath : null,
            'nextUrl' => $nextPath ? $this->getParameter('canonical_domain') . $nextPath : null,
            'hasDemo' => $hasDemo,
        ]);
    }
}
