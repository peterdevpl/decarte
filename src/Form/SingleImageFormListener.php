<?php

declare(strict_types=1);

namespace Decarte\Shop\Form;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\UploadedFile;

abstract class SingleImageFormListener implements EventSubscriberInterface
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
                ['upload', 10],
            ],
        ];
    }

    public function upload(FormEvent $event)
    {
        $data = $event->getData();

        if ($data['imageName']['image'] instanceof UploadedFile) {
            $file = $data['imageName']['image'];
            $destinationName = sha1_file($file->getRealPath()) . '.jpg';
            $file->move($this->imageDirectory, $destinationName);

            $data['imageName']['imageName'] = $destinationName;
            $event->setData($data);
        }
    }
}
