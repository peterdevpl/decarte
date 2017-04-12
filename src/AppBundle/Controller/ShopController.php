<?php

namespace AppBundle\Controller;

use AppBundle\Form\OrderSamplesType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ShopController extends Controller
{
    /**
     * @Route("/sklep/{type}", name="shop_list_collections", requirements={"type": "[0-9a-z\-]+"})
     * @param string $type
     */
    public function listCollectionsAction($type)
    {
        $em = $this->getDoctrine()->getManager();
        $productType = $em->getRepository('AppBundle:ProductType')->findBySlugName($type);
        if (!$productType) {
            throw $this->createNotFoundException('Nie znaleziono produktów tego typu');
        }

        $productType = $productType[0];
        $productCollections = $em->getRepository('AppBundle:ProductCollection')->getProductCollections($productType->getId());

        return $this->render('shop/listCollections.html.twig', [
            'productType' => $productType,
            'productCollections' => $productCollections,
        ]);
    }

    /**
     * @Route("/sklep/{type}/{slugName}", name="shop_view_collection", requirements={"type": "[0-9a-z\-]+", "slugName": "[a-z0-9\-]+"})
     * @param string $type Only for SEO.
     * @param string $slugName
     */
    public function viewCollectionAction($type, $slugName)
    {
        $em = $this->getDoctrine()->getManager();
        $productCollection = $em->getRepository('AppBundle:ProductCollection')->findBySlugName($slugName);
        if (!$productCollection) {
            throw $this->createNotFoundException('Nie znaleziono kolekcji produktów');
        }
        $productCollection = $productCollection[0];

        $productType = $productCollection->getProductType();
        $allCollections = $em->getRepository('AppBundle:ProductCollection')->getProductCollections($productType->getId());

        return $this->render('shop/viewCollection.html.twig', [
            'productCollection' => $productCollection,
            'allCollections' => $allCollections,
        ]);
    }

    /**
     * @Route("/sklep/{type}/{slugName}/{id}", name="shop_view_product", requirements={"type": "[0-9a-z\-]+", "slugName": "[a-z0-9\-]+", "id": "\d+"})
     * @param string $type Used only for SEO.
     * @param string $slugName Used only for SEO.
     * @param int $id
     */
    public function viewProduct($type, $slugName, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('AppBundle:Product')->find($id);
        if (!$product) {
            throw $this->createNotFoundException('Nie znaleziono produktu');
        }

        return $this->render('shop/viewProduct.html.twig', [
            'product' => $product,
        ]);

    }

    /**
     * @Route("/zamow/{id}", name="shop_order", requirements={"id": "\d+"})
     * @param int $id
     */
    public function orderAction($id)
    {

    }

    /**
     * @Route("/zamow-probki", name="shop_order_samples")
     */
    public function orderSamplesAction(Request $request)
    {
        $form = $this->createForm(OrderSamplesType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

        }

        return $this->render('shop/orderSamples.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
