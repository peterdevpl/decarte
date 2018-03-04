<?php

namespace Decarte\Shop\Form\Product;

use Decarte\Shop\Form\Product\Event\ProductTypeFormListener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class ProductTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nazwa'])
            ->add('title_seo', TextType::class, ['label' => 'Tytuł SEO'])
            ->add('isVisible', CheckboxType::class, [
                'label' => 'Typ widoczny na stronie', 'required' => false,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Opis', 'required' => false, 'attr' => ['rows' => 4],
            ])
            ->add('description_seo', TextareaType::class, [
                'label' => 'Opis SEO', 'required' => false, 'attr' => ['rows' => 4],
            ])
            ->add('save', SubmitType::class, ['label' => 'Zapisz typ produktu']);

        if ($builder->getData()->getId()) {
            $builder->add('delete', SubmitType::class, [
                'label' => 'Usuń dział',
                'attr' => ['class' => 'btn-danger btn-aside'],
            ]);
        }
    }
}
