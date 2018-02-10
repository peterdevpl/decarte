<?php

namespace Decarte\Shop\Controller;

use Decarte\Shop\Form\PageForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{
    /**
     * @Route("/admin", name="admin_index")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $productTypes = $em->getRepository('ProductBundle:ProductType')->getProductTypes(false);
        $pages = $em->getRepository('AppBundle:Page')->getPages();

        return $this->render('admin/index.html.twig', [
            'productTypes' => $productTypes,
            'pages' => $pages,
        ]);
    }

    /**
     * @Route("/admin/page/{pageId}", name="admin_editpage", requirements={"pageId": "\d+"})
     */
    public function editPageAction(Request $request, $pageId)
    {
        $em = $this->getDoctrine()->getManager();
        $page = $em->getRepository('AppBundle:Page')->find($pageId);
        if (!$page) {
            throw $this->createNotFoundException('Nie znaleziono strony');
        }

        $form = $this->createForm(PageForm::class, $page);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $page = $form->getData();

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
