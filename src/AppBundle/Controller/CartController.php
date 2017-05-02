<?php

namespace AppBundle\Controller;

use AppBundle\Entity\CartItem;
use AppBundle\Repository\CartRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class CartController extends Controller
{
    /**
     * @Route("/koszyk", name="cart_index")
     * @return Response
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $productRepository = $em->getRepository('AppBundle:Product');

        $repository = new CartRepository(new Session(), $productRepository);
        $cart = $repository->getCart();

        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
        ]);
    }

    /**
     * @Route("/koszyk/dodaj", name="cart_add_item")
     * @param Request $request
     * @return Response
     */
    public function addItemAction(Request $request)
    {
        $productId = $request->get('product_id');
        $em = $this->getDoctrine()->getManager();
        $productRepository = $em->getRepository('AppBundle:Product');
        $product = $productRepository->find($productId);

        if (!$product ||
            !$product->isVisible() ||
            !$product->getProductSeries()->isVisible() ||
            !$product->getProductSeries()->getProductCollection()->isVisible() ||
            !$product->getProductSeries()->getProductCollection()->getProductType()->isVisible()) {
            throw $this->createNotFoundException('Nie znaleziono produktu');
        }

        $repository = new CartRepository(new Session(), $productRepository);
        $repository->getCart()->addItem(new CartItem($product, 1));
        $repository->persist();

        return $this->redirectToRoute('cart_index');
    }
}
