<?php

namespace OrderBundle\Form;

use OrderBundle\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShippingDetailsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class, ['label' => 'Imię i nazwisko'])
            ->add('street', TextType::class, ['label' => 'Ulica, nr domu i mieszkania'])
            ->add('postalCode', TextType::class, ['label' => 'Kod pocztowy'])
            ->add('city', TextType::class, ['label' => 'Miasto'])
            ->add('email', EmailType::class, ['label' => 'E-mail'])
            ->add('phone', TextType::class, ['label' => 'Numer telefonu'])
            ->add('notes', TextareaType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'rows' => 3,
                    'cols' => 80,
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Dalej',
                'attr' => ['class' => 'btn btn-primary'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(['data_class' => Order::class]);
    }
}
