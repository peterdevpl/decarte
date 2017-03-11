<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\DataTransformerInterface;

class StringImageTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        return ['imageName' => $value, 'image' => null];
    }

    public function reverseTransform($value)
    {
        return $value['imageName'];
    }
}