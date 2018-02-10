<?php

namespace Decarte\Shop\Form\Product\Event;

use Cocur\Slugify\SlugifyInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class ProductTypeFormListener implements EventSubscriberInterface
{
    protected $slugify;

    public function __construct(SlugifyInterface $slugify)
    {
        $this->slugify = $slugify;
    }

    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SUBMIT => [
                ['slugify', 5],
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
