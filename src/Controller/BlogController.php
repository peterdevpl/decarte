<?php

declare(strict_types=1);

namespace Decarte\Shop\Controller;

use Decarte\Shop\Repository\BlogPostRepository;
use Decarte\Shop\Service\Url\BlogPostUrl;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class BlogController extends AbstractController
{
    /**
     * @Route("/poradnik/{slugName}", name="blog_post", requirements={"slugName": "[0-9a-z\-]+"})
     */
    public function showAction(
        string $slugName,
        BlogPostRepository $blogPostRepository,
        BlogPostUrl $blogPostUrl
    ): Response {
        $post = $blogPostRepository->findOneByName($slugName);
        if (!$post) {
            throw $this->createNotFoundException('Nie znaleziono strony');
        }

        return $this->render('blog/post.html.twig', [
            'post' => $post,
            'canonicalUrl' => $this->getParameter('canonical_domain') . $blogPostUrl->generate($post),
        ]);
    }
}
