<?php

declare(strict_types=1);

namespace Decarte\Shop\Form\Product;

use Decarte\Shop\Entity\Product\ProductCollection;
use Decarte\Shop\Entity\Product\ProductType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProductCollectionForm extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults(['data_class' => ProductCollection::class])
            ->setRequired(['product_types']);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('productType', EntityType::class, [
                'class' => ProductType::class,
                'choices' => $options['product_types'],
                'label' => 'Typ',
            ])
            ->add('name', TextType::class, ['label' => 'Nazwa'])
            ->add('title_seo', TextType::class, ['label' => 'Tytuł SEO'])
            ->add('isVisible', CheckboxType::class, [
                'label' => 'Kolekcja widoczna na stronie',
                'required' => false,
            ])
            ->add('minimumQuantity', NumberType::class, ['label' => 'Minimalna liczba zamawianych sztuk'])
            ->add('shortDescription', TextareaType::class, [
                'label' => 'Opis na stronie głównej i SEO',
                'required' => false,
                'attr' => ['rows' => 4],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Pełny opis',
                'required' => false,
                'attr' => ['rows' => 4],
            ])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Miniaturka',
                'required' => false,
                'imagine_pattern' => 'product_collection_thumb',
            ])
            ->add('save', SubmitType::class, ['label' => 'Zapisz kolekcję']);

        if ($builder->getData()->getId()) {
            $builder->add('delete', SubmitType::class, [
                'label' => 'Usuń kolekcję',
                'attr' => ['class' => 'btn-danger btn-aside'],
            ]);
        }
    }
}
