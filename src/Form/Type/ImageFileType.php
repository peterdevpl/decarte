<?php

declare(strict_types=1);

namespace Decarte\Shop\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class ImageFileType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(['compound' => true])
            ->setRequired(['image_url']);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('imageName', HiddenType::class);

        $builder->add('image', FileType::class, [
            'label' => false,
            'required' => false,
            'mapped' => false,
        ]);
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $fileName = $form->get('imageName')->getData();
        $view->vars['url'] = $fileName ? $options['image_url'] . DIRECTORY_SEPARATOR . $fileName : null;
        $view->children['image']->vars = array_replace($view->vars, $view->children['image']->vars);
    }
}
