<?php

namespace ProductBundle\Controller;

use ProductBundle\Entity\ProductCollection;
use ProductBundle\Entity\ProductSeries;
use ProductBundle\Entity\ProductType;
use ProductBundle\Entity\Product;
use ProductBundle\Form\ProductCollectionForm;
use ProductBundle\Form\ProductSeriesForm;
use ProductBundle\Form\ProductTypeForm;
use ProductBundle\Form\ProductForm;
use ProductBundle\Repository\ProductRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{
    protected $imagesToDelete = null;

    /**
     * @Route("/admin/addProductType", name="admin_add_product_type")
     * @param Request $request
     */
    public function addProductTypeAction(Request $request)
    {
        $productType = new ProductType();
        $productType->setIsVisible(true);

        return $this->editProductType($request, $productType, 'Typ produktu został dodany');
    }

    /**
     * @Route("/admin/editProductType/{typeId}", name="admin_edit_product_type", requirements={"typeId": "\d+"})
     * @param Request $request
     * @param int $typeId
     */
    public function editProductTypeAction(Request $request, $typeId)
    {
        $em = $this->getDoctrine()->getManager();
        $productType = $em->getRepository('ProductBundle:ProductType')->find($typeId);
        if (!$productType) {
            throw $this->createNotFoundException('Nie znaleziono typu produktów');
        }

        return $this->editProductType($request, $productType, 'Typ produktu został zapisany');
    }

    protected function editProductType(Request $request, ProductType $productType, string $successMessage)
    {
        $form = $this->createForm(ProductTypeForm::class, $productType, [
            'slugify' => $this->get('slugify'),
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $productType = $form->getData();

            $em = $this->getDoctrine()->getManager();
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
    public function listProductCollectionsAction($type)
    {
        $em = $this->getDoctrine()->getManager();
        $productType = $em->getRepository('ProductBundle:ProductType')->find($type);
        if (!$productType) {
            throw $this->createNotFoundException('Nie znaleziono typu produktów');
        }
        $productCollections = $em->getRepository('ProductBundle:ProductCollection')->getProductCollections($type, false);

        return $this->render('admin/productCollections.html.twig', [
            'productType' => $productType,
            'productCollections' => $productCollections,
        ]);
    }

    /**
     * @Route("/admin/productCollection/{collection}", name="admin_product_collection", requirements={"collection": "\d+"})
     */
    public function viewProductCollectionAction($collection)
    {
        $em = $this->getDoctrine()->getManager();
        $productCollection = $em->getRepository('ProductBundle:ProductCollection')->find($collection);
        if (!$productCollection) {
            throw $this->createNotFoundException('Nie znaleziono kolekcji produktów');
        }

        return $this->render('admin/productCollection.html.twig', [
            'productCollection' => $productCollection,
        ]);
    }

    /**
     * @Route("/admin/addProductCollection/{typeId}", name="admin_add_product_collection", requirements={"typeId": "\d+"})
     */
    public function addProductCollectionAction(Request $request, $typeId)
    {
        $em = $this->getDoctrine()->getManager();
        $productType = $em->getRepository('ProductBundle:ProductType')->find($typeId);
        if (!$productType) {
            throw $this->createNotFoundException('Nie znaleziono typu produktu');
        }

        $productCollection = new ProductCollection();
        $productCollection->setProductType($productType)->setIsVisible(true);

        return $this->editProductCollection($request, $productCollection, 'Kolekcja została dodana');
    }

    /**
     * @Route("/admin/editProductCollection/{collectionId}", name="admin_edit_product_collection", requirements={"collectionId": "\d+"})
     */
    public function editProductCollectionAction(Request $request, $collectionId)
    {
        $em = $this->getDoctrine()->getManager();
        $productCollection = $em->getRepository('ProductBundle:ProductCollection')->find($collectionId);
        if (!$productCollection) {
            throw $this->createNotFoundException('Nie znaleziono kolekcji produktów');
        }

        return $this->editProductCollection($request, $productCollection, 'Kolekcja została zapisana');
    }

    protected function editProductCollection(Request $request, ProductCollection $productCollection, string $successMessage)
    {
        $form = $this->createForm(ProductCollectionForm::class, $productCollection, [
            'images' => [
                'image' => [
                    'url' => $this->getParameter('image.collection.url'),
                    'directory' => $this->getParameter('image.collection.directory'),
                    'width' => $this->getParameter('image.collection.width'),
                    'height' => $this->getParameter('image.collection.height'),
                    'quality' => $this->getParameter('image.collection.quality'),
                ],
            ],
            'default_image' => 'image',
            'deletion_queue' => $this->getImagesDeletionQueue(),
            'slugify' => $this->get('slugify'),
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $productCollection = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($productCollection);
            $em->flush();

            $this->deleteOldImages();
            $this->addFlash('notice', $successMessage);

            return $this->redirectToRoute('admin_edit_product_collection', ['collectionId' => $productCollection->getId()]);
        }

        return $this->render('admin/editProductCollection.html.twig', [
            'productCollection' => $productCollection,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/move/{class}/{id}/{direction}", name="admin_move", requirements={"class": "Product|ProductSeries|ProductCollection", "id": "\d+", "direction": "up|down"})
     * @param Request $request
     * @param string $class Entity class
     * @param int $id Entity ID
     * @param string $direction "up" or "down"
     * @return RedirectResponse
     */
    public function moveAction(Request $request, $class, $id, $direction)
    {
        $em = $this->getDoctrine()->getManager();
        $object = $em->getRepository('ProductBundle:' . $class);
        $method = 'move' . ucfirst($direction);
        $object->$method($id);

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }

    /**
     * @Route("/admin/addProductSeries/{collectionId}", name="admin_add_product_series", requirements={"collectionId": "\d+"})
     */
    public function addProductSeriesAction(Request $request, $collectionId)
    {
        $em = $this->getDoctrine()->getManager();
        $productCollection = $em->getRepository('ProductBundle:ProductCollection')->find($collectionId);
        if (!$productCollection) {
            throw $this->createNotFoundException('Nie znaleziono kolekcji produktów');
        }

        $productSeries = new ProductSeries();
        $productSeries->setProductCollection($productCollection)->setIsVisible(true);

        return $this->editProductSeries($request, $productSeries, 'Seria została dodana');
    }

    /**
     * @Route("/admin/editProductSeries/{seriesId}", name="admin_edit_product_series", requirements={"seriesId": "\d+"})
     */
    public function editProductSeriesAction(Request $request, $seriesId)
    {
        $em = $this->getDoctrine()->getManager();
        $productSeries = $em->getRepository('ProductBundle:ProductSeries')->find($seriesId);
        if (!$productSeries) {
            throw $this->createNotFoundException('Nie znaleziono serii produktów');
        }

        return $this->editProductSeries($request, $productSeries, 'Seria została zapisana');
    }

    protected function editProductSeries(Request $request, ProductSeries $productSeries, string $successMessage)
    {
        $form = $this->createForm(ProductSeriesForm::class, $productSeries, [
            'images' => [
                'image' => [
                    'url' => $this->getParameter('image.series.url'),
                    'directory' => $this->getParameter('image.series.directory'),
                    'width' => $this->getParameter('image.series.width'),
                    'height' => $this->getParameter('image.series.height'),
                    'quality' => $this->getParameter('image.series.quality'),
                ],
            ],
            'default_image' => 'image',
            'deletion_queue' => $this->getImagesDeletionQueue(),
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $productSeries = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($productSeries);
            $em->flush();

            $this->deleteOldImages();
            $this->addFlash('notice', $successMessage);

            return $this->redirectToRoute('admin_edit_product_series', ['seriesId' => $productSeries->getId()]);
        }

        return $this->render('admin/editProductSeries.html.twig', [
            'productSeries' => $productSeries,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/addProduct/{seriesId}", name="admin_add_product", requirements={"seriesId": "\d+"})
     */
    public function addProductAction(Request $request, $seriesId)
    {
        $em = $this->getDoctrine()->getManager();
        $productSeries = $em->getRepository('ProductBundle:ProductSeries')->find($seriesId);
        if (!$productSeries) {
            throw $this->createNotFoundException('Nie znaleziono serii produktów');
        }

        $product = new Product();
        $product->setProductSeries($productSeries)->setIsVisible(true)->setHasDemo(true);

        return $this->editProduct($request, $product, 'Produkt został dodany');
    }

    /**
     * @Route("/admin/editProduct/{productId}", name="admin_edit_product", requirements={"productId": "\d+"})
     */
    public function editProductAction(Request $request, $productId)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('ProductBundle:Product');
        $product = $repository->find($productId);
        if (!$product) {
            throw $this->createNotFoundException('Nie znaleziono produktu');
        }

        return $this->editProduct($request, $repository, $product, 'Produkt został zapisany');
    }

    protected function editProduct(
        Request $request,
        ProductRepository $repository,
        Product $product,
        string $successMessage
    ) {
        $form = $this->createForm(ProductForm::class, $product, [
            'images' => [
                'big' => [
                    'url' => $this->getParameter('image.product.big.url'),
                    'directory' => $this->getParameter('image.product.big.directory'),
                    'width' => $this->getParameter('image.product.big.width'),
                    'height' => $this->getParameter('image.product.big.height'),
                    'quality' => $this->getParameter('image.product.big.quality'),
                ],
                'small' => [
                    'url' => $this->getParameter('image.product.small.url'),
                    'directory' => $this->getParameter('image.product.small.directory'),
                    'width' => $this->getParameter('image.product.small.width'),
                    'height' => $this->getParameter('image.product.small.height'),
                    'quality' => $this->getParameter('image.product.small.quality'),
                ],
            ],
            'default_image' => 'small',
            'deletion_queue' => $this->getImagesDeletionQueue(),
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('delete')->isClicked()) {
                $collection = $product->getProductSeries()->getProductCollection();
                $repository->remove($product);
                $this->addFlash('notice', 'Produkt został usunięty');

                return $this->redirectToRoute('admin_product_collection', [
                    'collection' => $collection->getId(),
                ]);
            }

            $product = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $this->deleteOldImages();
            $this->addFlash('notice', $successMessage);

            return $this->redirectToRoute('admin_edit_product', ['productId' => $product->getId()]);
        }

        return $this->render('admin/editProduct.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    protected function getImagesDeletionQueue()
    {
        if ($this->imagesToDelete === null) {
            $this->imagesToDelete = new \SplQueue();
        }
        return $this->imagesToDelete;
    }

    protected function deleteOldImages()
    {
        $queue = $this->getImagesDeletionQueue();
        while (!$queue->isEmpty()) {
            $path = $queue->dequeue();
            if (file_exists($path)) {
                unlink($path);
            }
        }
    }
}
