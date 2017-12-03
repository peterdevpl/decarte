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
                $newImage = [
                    'sort' => $sort,
                    'originalName' => $this->saveOriginalFile($imageForm['image']),
                ];

                $thumbnail = new Thumbnail($imageForm['image']);
                foreach ($this->options['images'] as $imageName => $imageOptions) {
                    $file = $thumbnail->createCroppedThumbnail(
                        $imageOptions['directory'],
                        $imageOptions['width'],
                        $imageOptions['height'],
                        $imageOptions['quality']
                    );

                    if (!empty($imageForm[$imageName . 'Name'])) {
                        $this->scheduleForDeletion($imageOptions['directory'] . DIRECTORY_SEPARATOR . $imageForm[$imageName . 'Name']);
                    }

                    $newImage[$imageName . 'Name'] = $file->getFilename();
                }

                $data['images'][$sort] = $newImage;
            }
        }

        $event->setData($data);
    }

    protected function saveOriginalFile(File $image): string
    {
        $uploadedPath = $image->getRealPath();
        $originalName = sha1_file($uploadedPath) . '.jpg';
        copy($uploadedPath, $this->options['original_image_directory'] . '/' . $originalName);

        return $originalName;
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
