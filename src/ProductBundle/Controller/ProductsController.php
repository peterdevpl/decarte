<?php

namespace ProductBundle\Controller;

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
    public function listCollectionsAction($type)
    {
        $em = $this->getDoctrine()->getManager();
        $productType = $em->getRepository('ProductBundle:ProductType')->findBySlugName($type);
        if (!$productType) {
            throw $this->createNotFoundException('Nie znaleziono produktów tego typu');
        }

        $productCollections = $em->getRepository('ProductBundle:ProductCollection')->getProductCollections($productType->getId());
        if (!$productCollections) {
            throw $this->createNotFoundException('Nie znaleziono produktów tego typu');
        }

        if (!$productType->hasFrontPage()) {
            return $this->redirectToRoute('shop_view_collection', [
                'type' => $productType->getSlugName(),
                'slugName' => $productCollections[0]->getSlugName(),
            ]);
        }

        return $this->render('shop/listCollections.html.twig', [
            'productType' => $productType,
            'productCollections' => $productCollections,
        ]);
    }

    /**
     * @Route("/sklep/{type}/{slugName}", name="shop_view_collection", requirements={"type": "[0-9a-z\-]+", "slugName": "[a-z0-9\-]+"})
     * @param string $type
     * @param string $slugName
     * @return Response
     */
    public function viewCollectionAction($type, $slugName)
    {
        $em = $this->getDoctrine()->getManager();
        $productCollection = $em->getRepository('ProductBundle:ProductCollection')->findBySlugName($type, $slugName);
        if (!$productCollection) {
            throw $this->createNotFoundException('Nie znaleziono kolekcji produktów');
        }

        $productType = $productCollection->getProductType();
        $allCollections = $em->getRepository('ProductBundle:ProductCollection')->getProductCollections($productType->getId());
        $allSeries = $em->getRepository('ProductBundle:ProductSeries')->getFromCollection($productCollection);

        return $this->render('shop/viewCollection.html.twig', [
            'productCollection' => $productCollection,
            'allCollections' => $allCollections,
            'allSeries' => $allSeries,
        ]);
    }

    /**
     * @Route("/sklep/{type}/{slugName}/{id}", name="shop_view_product", requirements={"type": "[0-9a-z\-]+", "slugName": "[a-z0-9\-]+", "id": "\d+"})
     * @param string $type Used only for SEO.
     * @param string $slugName Used only for SEO.
     * @param int $id
     * @return Response
     */
    public function viewProductAction($type, $slugName, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('ProductBundle:Product')->find($id);
        if (!$product ||
            !$product->isVisible() ||
            !$product->getProductSeries()->isVisible() ||
            !$product->getProductSeries()->getProductCollection()->isVisible() ||
            !$product->getProductSeries()->getProductCollection()->getProductType()->isVisible()) {
            throw $this->createNotFoundException('Nie znaleziono produktu');
        }

        return $this->render('shop/viewProduct.html.twig', [
            'product' => $product,
        ]);
    }
}
