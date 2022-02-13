<?php

declare(strict_types=1);

namespace Decarte\Shop\Command;

use Decarte\Shop\Repository\Product\ProductRepository;
use Decarte\Shop\Service\GoogleExport;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExportToGoogleCommand extends Command
{
    private $exportService;
    private $productRepository;

    public function __construct(GoogleExport $exportService, ProductRepository $productRepository)
    {
        parent::__construct();
        $this->exportService = $exportService;
        $this->productRepository = $productRepository;
    }

    protected function configure(): void
    {
        $this
            ->setName('product:export-to-google')
            ->setDescription('Exports products data to Google Merchant Center')
            ->addArgument('id', InputArgument::OPTIONAL, 'Product ID');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $productId = $input->getArgument('id');
        if ($productId) {
            $this->exportSingleProduct($output, (int) $productId);
        } else {
            $this->exportAllProducts($output);
        }

        return 0;
    }

    protected function exportSingleProduct(OutputInterface $output, int $productId): void
    {
        $output->writeln('Exporting product ID=' . $productId);

        $product = $this->productRepository->find($productId);
        $response = $this->exportService->exportProduct($product);

        var_dump($response);
    }

    protected function exportAllProducts(OutputInterface $output): void
    {
        $products = $this->productRepository->findAllVisibleProducts();
        $output->writeln(sprintf('Exporting %d products', count($products)));

        $response = $this->exportService->exportProductsCollection($products);

        var_dump($response);
    }
}
