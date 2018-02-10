<?php

namespace Decarte\Shop\Form\Product\Event;

use Cocur\Slugify\SlugifyInterface;
use Decarte\Shop\Form\SingleImageFormListener;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class ProductCollectionFormListener extends SingleImageFormListener
{
    private $slugify;

    public function __construct(string $imageDirectory, SlugifyInterface $slugify)
    {
        parent::__construct($imageDirectory);
        $this->slugify = $slugify;
    }

    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SUBMIT => [
                ['slugify', 5],
                ['upload', 10],
            ],
        ];
    }

    public function slugify(FormEvent $event)
    {
        $data = $event->getData();
        $data['slugName'] = $this->slugify->slugify($data['name']);
        $event->setData($data);
    }
}
