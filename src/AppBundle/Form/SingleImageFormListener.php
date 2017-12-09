<?php
namespace AppBundle\Form;

use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

abstract class SingleImageFormListener implements EventSubscriberInterface
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
            $file->move($this->options['image_directory'], $destinationName);

            $data['imageName']['imageName'] = $destinationName;
            $event->setData($data);
        }
    }

    protected function scheduleForDeletion(string $path)
    {
        $this->options['deletion_queue']->enqueue($path);
    }
}
