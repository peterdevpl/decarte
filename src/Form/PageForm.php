<?php

declare(strict_types=1);

namespace Decarte\Shop\Form;

use Decarte\Shop\Entity\Page;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageForm extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => Page::class]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Tytuł'])
            ->add('contents', TextareaType::class, ['label' => 'Treść', 'attr' => ['rows' => 20]])
            ->add('save', SubmitType::class, ['label' => 'Zapisz stronę']);
    }
}
