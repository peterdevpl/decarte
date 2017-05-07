<?php

namespace ProductBundle\Form;

use ProductBundle\Entity\Product;
use ProductBundle\Form\Event\ProductFormListener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductForm extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(['data_class' => Product::class])
            ->setRequired(['images', 'default_image', 'deletion_queue']);
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('productSeries', EntityType::class, [
                'class' => 'ProductBundle:ProductSeries',
                'choices' => $builder->getData()->getProductSeries()->getProductCollection()->getProductSeries(),
                'label' => 'Seria',
            ])
            ->add('name', TextType::class, ['label' => 'Symbol'])
            ->add('price', MoneyType::class, ['label' => 'Cena', 'currency' => 'PLN'])
            ->add('isVisible', CheckboxType::class, ['label' => 'Produkt widoczny na stronie', 'required' => false])
            ->add('hasDemo', CheckboxType::class, ['label' => 'Produkt dostępny w serwisie próbkowym', 'required' => false])
            ->add('descriptionSEO', TextareaType::class, ['label' => 'Ogólny opis, u góry strony', 'required' => false])
            ->add('description', TextareaType::class, ['label' => 'Szczegółowy opis u dołu strony', 'required' => false, 'attr' => ['rows' => 10]])
            ->add('images', CollectionType::class, [
                'label' => false,
                'entry_type' => ProductImageFileType::class,
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'entry_options' => [
                    'images' => $options['images'],
                    'default_image' => $options['default_image'],
                ],
            ])
            ->addEventSubscriber(new ProductFormListener($options));

        $builder->add('save', SubmitType::class, ['label' => 'Zapisz produkt']);
        
        if ($builder->getData()->getId()) {
            $builder->add('delete', SubmitType::class, ['label' => 'Usuń produkt']);
        }
    }
}
