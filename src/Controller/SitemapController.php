<?php declare(strict_types = 1);

namespace Decarte\Shop\Controller;

use Decarte\Shop\Repository\Product\ProductRepository;
use Decarte\Shop\Service\Url\ProductUrl;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class SitemapController extends Controller
{
    /**
     * @Route("/sitemap.xml", name="sitemap")
     * @return Response
     */
    public function indexAction(ProductRepository $productsRepository, ProductUrl $productUrl): Response
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        $products = $productsRepository->findAllVisibleProducts();

        foreach ($products as $product) {
            $url = $productUrl->generate($product, true);
            $date = $product->getUpdatedAt() ?? $product->getCreatedAt();
            $dateFormatted = $date->format('Y-m-d');
            $xml .= "<url><loc>{$url}</loc><lastmod>{$dateFormatted}</lastmod>" .
                "<changefreq>daily</changefreq><priority>0.8</priority></url>";
        }

        $xml .= '</urlset>';

        return new Response($xml, 200, ['Content-Type' => 'text/xml']);
    }
}
