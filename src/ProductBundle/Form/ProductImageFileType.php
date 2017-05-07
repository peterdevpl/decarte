<?php

namespace ProductBundle\Form;

use AppBundle\Form\Type\ImageFileType;
use ProductBundle\Entity\ProductImage;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductImageFileType extends ImageFileType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefault('data_class', ProductImage::class);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->add('sort', HiddenType::class);
    }

    public function getBlockPrefix()
    {
        return 'image_file';
    }
}
