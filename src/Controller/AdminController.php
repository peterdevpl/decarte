<?php

declare(strict_types=1);

namespace Decarte\Shop\Controller;

use Decarte\Shop\Form\PageForm;
use Decarte\Shop\Repository\PageRepository;
use Decarte\Shop\Repository\Product\ProductTypeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class AdminController extends AbstractController
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @Route("/admin", name="admin_index")
     */
    public function indexAction(ProductTypeRepository $productTypeRepository, PageRepository $pageRepository): Response
    {
        $productTypes = $productTypeRepository->getProductTypes(false);
        $pages = $pageRepository->getPages();

        return $this->render('admin/index.html.twig', [
            'productTypes' => $productTypes,
            'pages' => $pages,
        ]);
    }

    /**
     * @Route("/admin/page/{pageId}", name="admin_editpage", requirements={"pageId": "\d+"})
     */
    public function editPageAction(Request $request, int $pageId, PageRepository $pageRepository): Response
    {
        $page = $pageRepository->find($pageId);
        if (!$page) {
            throw $this->createNotFoundException('Nie znaleziono strony');
        }

        $form = $this->createForm(PageForm::class, $page);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $page = $form->getData();

            $em = $this->doctrine->getManager();
            $em->persist($page);
            $em->flush();

            $this->addFlash('notice', 'Strona została zapisana');

            return $this->redirectToRoute('admin_editpage', ['pageId' => $page->getId()]);
        }

        return $this->render('admin/editPage.html.twig', [
            'page' => $page,
            'form' => $form->createView(),
        ]);
    }
}
