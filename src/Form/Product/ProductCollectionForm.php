<?php

declare(strict_types=1);

namespace Decarte\Shop\Form\Product;

use Decarte\Shop\Entity\Product\ProductCollection;
use Decarte\Shop\Entity\Product\ProductType;
use Decarte\Shop\Form\Product\Event\ProductCollectionFormListener;
use Decarte\Shop\Form\Type\StringImageFileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductCollectionForm extends AbstractType
{
    private $listener;
    private $imageUrl;

    public function __construct(ProductCollectionFormListener $listener, string $imageUrl)
    {
        $this->listener = $listener;
        $this->imageUrl = $imageUrl;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(['data_class' => ProductCollection::class])
            ->setRequired(['product_types']);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
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
            ->add('imageName', StringImageFileType::class, [
                'label' => 'Miniaturka',
                'required' => false,
                'image_url' => $this->imageUrl,
            ])
            ->add('save', SubmitType::class, ['label' => 'Zapisz kolekcję'])
            ->addEventSubscriber($this->listener);

        if ($builder->getData()->getId()) {
            $builder->add('delete', SubmitType::class, [
                'label' => 'Usuń kolekcję',
                'attr' => ['class' => 'btn-danger btn-aside'],
            ]);
        }
    }
}
