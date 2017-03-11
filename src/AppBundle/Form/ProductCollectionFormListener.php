<?php
namespace AppBundle\Form;

use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class ProductCollectionFormListener extends SingleImageFormListener
{
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
        $data['slugName'] = $this->options['slugify']->slugify($data['name']);
        $event->setData($data);
    }
}
