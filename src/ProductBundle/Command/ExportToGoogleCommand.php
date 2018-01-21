<?php

namespace ProductBundle\Command;

use ProductBundle\Repository\ProductRepository;
use ProductBundle\Service\GoogleExport;
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

    protected function configure()
    {
        $this
            ->setName('product:export-to-google')
            ->setDescription('Exports products data to Google Merchant Center')
            ->addArgument('id', InputArgument::REQUIRED, 'Product ID');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $productId = $input->getArgument('id');
        $output->writeln('Exporting product ID=' . $productId);

        $product = $this->productRepository->find($productId);
        $response = $this->exportService->exportProduct($product);

        var_dump($response);
    }
}
