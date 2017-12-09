<?php

namespace ProductBundle\Form\Event;

use AppBundle\Upload\Thumbnail;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\File\File;
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
            FormEvents::SUBMIT => [
                ['onSubmit', 5],
            ],
        ];
    }

    public function onPreSubmit(FormEvent $event)
    {
        $data = $event->getData();
        if (empty($data['images'])) {
            return;
        }

        foreach ($data['images'] as $sort => $imageForm) {
            if ($imageForm['image'] instanceof UploadedFile) {
                $file = $imageForm['image'];
                $destinationName = sha1_file($file->getRealPath()) . '.jpg';
                $file->move($this->options['image_directory'], $destinationName);

                $data['images'][$sort] = [
                    'imageName' => $destinationName,
                    'sort' => $sort,
                ];
            }
        }

        $event->setData($data);
    }

    protected function scheduleForDeletion(string $path)
    {
        $this->options['deletion_queue']->enqueue($path);
    }

    public function onSubmit(FormEvent $event)
    {
        /** @var \ProductBundle\Entity\Product $data */
        $data = $event->getData();

        foreach ($data->getImages() as $image) {
            $image->setProduct($data);
        }

        $event->setData($data);
    }
}
