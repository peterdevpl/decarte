<?php

declare(strict_types=1);

namespace Decarte\Shop\Form\Product\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProductFormListener implements EventSubscriberInterface
{
    protected $imageDirectory;

    public function __construct(string $imageDirectory)
    {
        $this->imageDirectory = $imageDirectory;
    }

    public static function getSubscribedEvents(): array
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
                $file->move($this->imageDirectory, $destinationName);

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
