<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Entity\SamplesOrder;
use AppBundle\Entity\SamplesOrderItem;
use AppBundle\Form\OrderSamplesType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ShopController extends Controller
{
    /**
     * @Route("/sklep/{type}", name="shop_list_collections", requirements={"type": "[0-9a-z\-]+"})
     * @param string $type
     * @return Response
     */
    public function listCollectionsAction($type)
    {
        $em = $this->getDoctrine()->getManager();
        $productType = $em->getRepository('AppBundle:ProductType')->findBySlugName($type);
        if (!$productType) {
            throw $this->createNotFoundException('Nie znaleziono produktów tego typu');
        }

        $productCollections = $em->getRepository('AppBundle:ProductCollection')->getProductCollections($productType->getId());
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
        $productCollection = $em->getRepository('AppBundle:ProductCollection')->findBySlugName($type, $slugName);
        if (!$productCollection) {
            throw $this->createNotFoundException('Nie znaleziono kolekcji produktów');
        }

        $productType = $productCollection->getProductType();
        $allCollections = $em->getRepository('AppBundle:ProductCollection')->getProductCollections($productType->getId());
        $allSeries = $em->getRepository('AppBundle:ProductSeries')->getFromCollection($productCollection);

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
    public function viewProduct($type, $slugName, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('AppBundle:Product')->find($id);
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

    /**
     * @Route("/zamow/{id}", name="shop_order", requirements={"id": "\d+"})
     * @param int $id
     */
    public function orderAction($id)
    {

    }

    /**
     * @Route("/zamow-probki", name="shop_order_samples")
     * @param Request $request
     * @return Response
     */
    public function orderSamplesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $dummyProduct = $em->getRepository('AppBundle:Product')->find('10');

//        $dummyProduct = new Product();
        $item =  new SamplesOrderItem();
        $item->setProduct($dummyProduct);
        $order = new SamplesOrder();
        $order->addItem($item)->addItem($item);

        $em = $this->getDoctrine()->getManager();
        $productType = $em->getRepository('AppBundle:ProductType')->find(1);
        $products = $em->getRepository('AppBundle:Product')->findDemos($productType);

        $form = $this->createForm(OrderSamplesType::class, $order, [
            'products' => $products,
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

        }

        return $this->render('shop/orderSamples.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
