<?php

declare(strict_types=1);

namespace Decarte\Shop\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;

class StringImageFileType extends ImageFileType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->addModelTransformer(new StringImageTransformer());
    }

    public function getBlockPrefix(): string
    {
        return 'image_file';
    }
}
