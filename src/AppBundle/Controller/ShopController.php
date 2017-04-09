<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ShopController extends Controller
{
    /**
     * @Route("/sklep/zaproszenia-slubne", name="shop_list_collections")
     */
    public function listCollectionsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $productCollections = $em->getRepository('AppBundle:ProductCollection')->getProductCollections(1, false);

        return $this->render('shop/invitations.html.twig', [
            'productCollections' => $productCollections,
        ]);
    }

    /**
     * @Route("/sklep/zaproszenia-slubne/{slugName}", name="shop_view_collection", requirements={"slugName": "[a-z0-9\-]+"})
     */
    public function viewCollectionAction($slugName)
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
     * @Route("/sklep/zaproszenia-slubne/{slugName}/{id}", name="shop_view_product", requirements={"slugName": "[a-z0-9\-]+"})
     * @param string $slugName Used only for SEO.
     * @param int $id
     */
    public function viewProduct($slugName, $id)
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
}
