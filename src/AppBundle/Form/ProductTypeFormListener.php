<?php
namespace AppBundle\Form;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class ProductTypeFormListener implements EventSubscriberInterface
{
    protected $options;

    public function __construct(array $options)
    {
        $this->options = $options;
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
        $data['slugName'] = $this->options['slugify']->slugify($data['name']);
        $event->setData($data);
    }
}
