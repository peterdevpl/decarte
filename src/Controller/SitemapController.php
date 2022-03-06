<?php

declare(strict_types=1);

namespace Decarte\Shop\Controller;

use Decarte\Shop\Repository\BlogPostRepository;
use Decarte\Shop\Repository\PageRepository;
use Decarte\Shop\Repository\Product\ProductCollectionRepository;
use Decarte\Shop\Repository\Product\ProductRepository;
use Decarte\Shop\Repository\Product\ProductTypeRepository;
use Decarte\Shop\Service\Url\BlogPostUrl;
use Decarte\Shop\Service\Url\PageUrl;
use Decarte\Shop\Service\Url\ProductCollectionUrl;
use Decarte\Shop\Service\Url\ProductTypeUrl;
use Decarte\Shop\Service\Url\ProductUrl;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class SitemapController extends AbstractController
{
    /**
     * @Route("/sitemap.xml", name="sitemap")
     */
    public function indexAction(
        PageRepository $pageRepository,
        ProductTypeRepository $typeRepository,
        ProductCollectionRepository $collectionRepository,
        ProductRepository $productsRepository,
        BlogPostRepository $blogPostRepository,
        PageUrl $pageUrl,
        ProductTypeUrl $typeUrl,
        ProductCollectionUrl $collectionUrl,
        ProductUrl $productUrl,
        BlogPostUrl $blogPostUrl
    ): Response {
        $xml = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        $types = $typeRepository->getProductTypes();
        $collections = [];
        foreach ($types as $type) {
            $collections[$type->getId()] = $collectionRepository->getProductCollections($type->getId());
        }
        $products = $productsRepository->findAllVisibleProducts();
        $pages = $pageRepository->getPages();
        $posts = $blogPostRepository->findAll();

        foreach ($types as $type) {
            $url = $typeUrl->generate($type, true);
            $date = new \DateTimeImmutable();
            $dateFormatted = $date->format('Y-m-d');
            $xml .= "<url><loc>{$url}</loc><lastmod>{$dateFormatted}</lastmod>" .
                '<changefreq>daily</changefreq><priority>0.8</priority></url>';
        }

        foreach ($collections as $collectionsForType) {
            foreach ($collectionsForType as $collection) {
                $url = $collectionUrl->generate($collection, true);
                $date = new \DateTimeImmutable();
                $dateFormatted = $date->format('Y-m-d');
                $xml .= "<url><loc>{$url}</loc><lastmod>{$dateFormatted}</lastmod>" .
                    '<changefreq>daily</changefreq><priority>0.8</priority></url>';
            }
        }

        foreach ($products as $product) {
            $url = $productUrl->generate($product, true);
            $date = $product->getUpdatedAt() ?? $product->getCreatedAt();
            $dateFormatted = $date->format('Y-m-d');
            $xml .= "<url><loc>{$url}</loc><lastmod>{$dateFormatted}</lastmod>" .
                '<changefreq>daily</changefreq><priority>0.8</priority></url>';
        }

        foreach ($pages as $page) {
            $url = $pageUrl->generate($page, true);
            $date = $page->getUpdatedAt() ?? $page->getCreatedAt();
            $dateFormatted = $date->format('Y-m-d');
            $xml .= "<url><loc>{$url}</loc><lastmod>{$dateFormatted}</lastmod>" .
                '<changefreq>weekly</changefreq><priority>0.5</priority></url>';
        }

        foreach ($posts as $post) {
            $url = $blogPostUrl->generate($post, true);
            $date = $post->getUpdatedAt() ?? $post->getCreatedAt();
            $dateFormatted = $date->format('Y-m-d');
            $xml .= "<url><loc>{$url}</loc><lastmod>{$dateFormatted}</lastmod>" .
                '<changefreq>weekly</changefreq><priority>0.5</priority></url>';
        }

        $xml .= '</urlset>';

        return new Response($xml, 200, ['Content-Type' => 'text/xml']);
    }
}
