<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;

class StringImageFileType extends ImageFileType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->addModelTransformer(new StringImageTransformer());
    }

    public function getBlockPrefix()
    {
        return 'image_file';
    }
}
