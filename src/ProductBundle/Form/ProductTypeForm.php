<?php

namespace ProductBundle\Form;

use ProductBundle\Form\Event\ProductTypeFormListener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductTypeForm extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['slugify']);
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nazwa'])
            ->add('slugName', HiddenType::class)
            ->add('isVisible', CheckboxType::class, ['label' => 'Typ widoczny na stronie', 'required' => false])
            ->add('hasFrontPage', CheckboxType::class, ['label' => 'Pokaż stronę z miniaturkami kolekcji', 'required' => false])
            ->add('minimumQuantity', NumberType::class, ['label' => 'Minimalna liczba zamawianych sztuk'])
            ->add('description', TextareaType::class, ['label' => 'Opis', 'required' => false, 'attr' => ['rows' => 4]])
            ->add('save', SubmitType::class, ['label' => 'Zapisz typ produktu'])
            ->addEventSubscriber(new ProductTypeFormListener($options));

        if ($builder->getData()->getId()) {
            $builder->add('delete', SubmitType::class, [
                'label' => 'Usuń dział',
                'attr' => ['class' => 'btn-danger btn-aside'],
            ]);
        }
    }
}
