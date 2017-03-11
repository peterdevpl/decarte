<?php
namespace AppBundle\Form;

use AppBundle\Upload\Thumbnail;
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
            $thumbnail = new Thumbnail($data['imageName']['image']);
            foreach ($this->options['images'] as $imageName => $imageOptions) {
                $file = $thumbnail->createCroppedThumbnail(
                    $imageOptions['directory'],
                    $imageOptions['width'],
                    $imageOptions['height'],
                    $imageOptions['quality']
                );

                $data['imageName'][$imageName . 'Name'] = $file->getFilename();
            }
           $event->setData($data);
        }
    }
}
