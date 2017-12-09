<?php

namespace ProductBundle\Form\Event;

use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProductFormListener implements EventSubscriberInterface
{
    protected $options = [];

    public function __construct(array $options)
    {
        $this->options = $options;
    }

    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SUBMIT => [
                ['onPreSubmit', 5],
            ],
        ];
    }

    public function onPreSubmit(FormEvent $event)
    {
        $data = $event->getData();
        if (empty($data['images'])) {
            return;
        }

        $sort = 0;
        foreach ($data['images'] as $index => $imageForm) {
            if ($imageForm['image'] instanceof UploadedFile) {
                $file = $imageForm['image'];
                $destinationName = sha1_file($file->getRealPath()) . '.jpg';
                $file->move($this->options['image_directory'], $destinationName);

                $data['images'][$index] = [
                    'imageName' => $destinationName,
                    'sort' => ++$sort,
                ];
            } else {
                $sort = $imageForm['sort'];
            }
        }

        $event->setData($data);
    }
}
