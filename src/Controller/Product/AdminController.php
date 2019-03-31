<?php

declare(strict_types=1);

namespace Decarte\Shop\Controller\Product;

use Decarte\Shop\Entity\Product\ProductCollection;
use Decarte\Shop\Entity\Product\ProductType;
use Decarte\Shop\Entity\Product\Product;
use Decarte\Shop\Form\Product\ProductCollectionForm;
use Decarte\Shop\Form\Product\ProductTypeForm;
use Decarte\Shop\Form\Product\ProductForm;
use Decarte\Shop\Repository\Product\ProductCollectionRepository;
use Decarte\Shop\Repository\Product\ProductRepository;
use Decarte\Shop\Repository\Product\ProductTypeRepository;
use Decarte\Shop\Service\GoogleExport;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class AdminController extends AbstractController
{
    /**
     * @Route("/admin/addProductType", name="admin_add_product_type")
     *
     * @param Request $request
     */
    public function addProductTypeAction(Request $request): Response
    {
        $productType = new ProductType();
        $productType->setIsVisible(true);

        return $this->editProductType($request, $productType, 'Typ produktu został dodany');
    }

    /**
     * @Route("/admin/editProductType/{typeId}", name="admin_edit_product_type", requirements={"typeId": "\d+"})
     *
     * @param Request $request
     * @param int $typeId
     */
    public function editProductTypeAction(
        Request $request,
        int $typeId,
        ProductTypeRepository $productTypeRepository
    ): Response {
        $productType = $productTypeRepository->find($typeId);
        if (!$productType) {
            throw $this->createNotFoundException('Nie znaleziono typu produktów');
        }

        return $this->editProductType($request, $productType, 'Typ produktu został zapisany');
    }

    protected function editProductType(Request $request, ProductType $productType, string $successMessage)
    {
        $form = $this->createForm(ProductTypeForm::class, $productType);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if ($form->has('delete')) {
                /** @var ClickableInterface $button */
                $button = $form->get('delete');
                if ($button->isClicked()) {
                    $em->remove($productType);
                    $em->flush();
                    $this->addFlash('notice', 'Dział został usunięty');

                    return $this->redirectToRoute('admin_index');
                }
            }

            $productType = $form->getData();

            $em->persist($productType);
            $em->flush();

            $this->addFlash('notice', $successMessage);

            return $this->redirectToRoute('admin_edit_product_type', ['typeId' => $productType->getId()]);
        }

        return $this->render('admin/editProductType.html.twig', [
            'productType' => $productType,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/productCollections/{type}", name="admin_product_collections", requirements={"type": "\d+"})
     */
    public function listProductCollectionsAction(
        string $type,
        ProductTypeRepository $typeRepository,
        ProductCollectionRepository $collectionRepository
    ): Response {
        $productType = $typeRepository->find($type);
        if (!$productType) {
            throw $this->createNotFoundException('Nie znaleziono typu produktów');
        }
        $productCollections = $collectionRepository->getProductCollections($type, false);

        return $this->render('admin/productCollections.html.twig', [
            'productType' => $productType,
            'productCollections' => $productCollections,
        ]);
    }

    /**
     * @Route(
     *     "/admin/productCollection/{collection}",
     *     name="admin_product_collection",
     *     requirements={"collection": "\d+"}
     * )
     */
    public function viewProductCollectionAction(
        string $collection,
        ProductCollectionRepository $collectionRepository
    ): Response {
        $productCollection = $collectionRepository->find($collection);
        if (!$productCollection) {
            throw $this->createNotFoundException('Nie znaleziono kolekcji produktów');
        }

        return $this->render('admin/productCollection.html.twig', [
            'productCollection' => $productCollection,
        ]);
    }

    /**
     * @Route(
     *     "/admin/addProductCollection/{typeId}",
     *     name="admin_add_product_collection",
     *     requirements={"typeId": "\d+"}
     * )
     */
    public function addProductCollectionAction(
        Request $request,
        int $typeId,
        ProductTypeRepository $typeRepository,
        ProductTypeRepository $productTypeRepository
    ): Response {
        $productType = $typeRepository->find($typeId);
        if (!$productType) {
            throw $this->createNotFoundException('Nie znaleziono typu produktu');
        }

        $productCollection = new ProductCollection();
        $productCollection->setProductType($productType)->setIsVisible(true);

        return $this->editProductCollection(
            $request,
            $productCollection,
            $productTypeRepository,
            'Kolekcja została dodana'
        );
    }

    /**
     * @Route(
     *     "/admin/editProductCollection/{collectionId}",
     *     name="admin_edit_product_collection",
     *     requirements={"collectionId": "\d+"}
     * )
     */
    public function editProductCollectionAction(
        Request $request,
        int $collectionId,
        ProductCollectionRepository $collectionRepository,
        ProductTypeRepository $productTypeRepository
    ): Response {
        $productCollection = $collectionRepository->find($collectionId);
        if (!$productCollection) {
            throw $this->createNotFoundException('Nie znaleziono kolekcji produktów');
        }

        return $this->editProductCollection(
            $request,
            $productCollection,
            $productTypeRepository,
            'Kolekcja została zapisana'
        );
    }

    protected function editProductCollection(
        Request $request,
        ProductCollection $productCollection,
        ProductTypeRepository $productTypeRepository,
        string $successMessage
    ): Response {
        $productTypes = $productTypeRepository->findAll();

        $form = $this->createForm(ProductCollectionForm::class, $productCollection, [
            'product_types' => $productTypes,
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if ($form->has('delete')) {
                /** @var ClickableInterface $button */
                $button = $form->get('delete');
                if ($button->isClicked()) {
                    $type = $productCollection->getProductType();
                    $em->remove($productCollection);
                    $em->flush();
                    $this->addFlash('notice', 'Kolekcja została usunięta');

                    return $this->redirectToRoute('admin_product_collections', [
                        'type' => $type->getId(),
                    ]);
                }
            }

            $productCollection = $form->getData();

            $em->persist($productCollection);
            $em->flush();

            $this->addFlash('notice', $successMessage);

            return $this->redirectToRoute(
                'admin_edit_product_collection',
                ['collectionId' => $productCollection->getId()]
            );
        }

        return $this->render('admin/editProductCollection.html.twig', [
            'productCollection' => $productCollection,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route(
     *     "/admin/move/{id}/{direction}",
     *     name="admin_move_collection",
     *     requirements={"id": "\d+", "direction": "up|down"}
     * )
     *
     * @return RedirectResponse
     */
    public function moveCollectionAction(
        Request $request,
        int $id,
        string $direction,
        ProductCollectionRepository $collectionRepository
    ): RedirectResponse {
        $method = 'move' . ucfirst($direction);
        $collectionRepository->$method($id);

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);
    }

    /**
     * @Route("/admin/addProduct/{collectionId}", name="admin_add_product", requirements={"collectionId": "\d+"})
     */
    public function addProductAction(
        Request $request,
        int $collectionId,
        ProductCollectionRepository $collectionRepository,
        GoogleExport $googleExport
    ): Response {
        $productCollection = $collectionRepository->find($collectionId);
        if (!$productCollection) {
            throw $this->createNotFoundException('Nie znaleziono kolekcji produktów');
        }

        $product = new Product();
        $product->setProductCollection($productCollection)->setIsVisible(true)->setHasDemo(true);

        return $this->editProduct(
            $request,
            $product,
            $googleExport,
            'Produkt został dodany'
        );
    }

    /**
     * @Route("/admin/editProduct/{productId}", name="admin_edit_product", requirements={"productId": "\d+"})
     */
    public function editProductAction(
        Request $request,
        int $productId,
        ProductRepository $productRepository,
        GoogleExport $googleExport
    ): Response {
        $product = $productRepository->find($productId);
        if (!$product) {
            throw $this->createNotFoundException('Nie znaleziono produktu');
        }

        return $this->editProduct($request, $product, $googleExport, 'Produkt został zapisany');
    }

    protected function editProduct(
        Request $request,
        Product $product,
        GoogleExport $googleExport,
        string $successMessage
    ): Response {
        $form = $this->createForm(ProductForm::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if ($form->has('delete')) {
                /** @var ClickableInterface $button */
                $button = $form->get('delete');
                if ($button->isClicked()) {
                    $collection = $product->getProductCollection();
                    $em->remove($product);
                    $em->flush();
                    $this->addFlash('notice', 'Produkt został usunięty');

                    return $this->redirectToRoute('admin_product_collection', [
                        'collection' => $collection->getId(),
                    ]);
                }
            }

            $product = $form->getData();

            $em->persist($product);
            $em->flush();

            try {
                if ($product->isVisible()) {
                    $googleExport->exportProduct($product);
                } else {
                    $googleExport->deleteProduct($product);
                }
            } catch (\Exception $e) {
            }

            $this->addFlash('notice', $successMessage);

            return $this->redirectToRoute('admin_edit_product', ['productId' => $product->getId()]);
        }

        return $this->render('admin/editProduct.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/setProductPosition", name="admin_set_product_position")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function setProductPositionAction(Request $request, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($request->request->get('productId'));
        if (!$product) {
            throw $this->createNotFoundException('Nie znaleziono produktu');
        }

        $product->setSort($request->request->getInt('position'));

        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();

        return new Response();
    }
}
