<?php

namespace OrderBundle\Form;

use OrderBundle\Entity\Order;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
            ->add('name',TextType::class, ['label' => 'Imię i nazwisko', 'attr' => ['size' => 60]])
            ->add('street', TextType::class, ['label' => 'Adres (ulica lub miejscowość, numer)', 'attr' => ['size' => 60]])
            ->add('postalCode', TextType::class, [
                'label' => 'Kod pocztowy',
                'attr' => [
                    'size' => 6,
                ],
            ])
            ->add('city', TextType::class, ['label' => 'Miasto', 'attr' => ['size' => 60]])
            ->add('email', EmailType::class, ['label' => 'E-mail', 'attr' => ['size' => 60]])
            ->add('phone', TextType::class, ['label' => 'Numer telefonu', 'attr' => ['size' => 60]])
            ->add('notes', TextareaType::class, [
                'label' => 'Uwagi lub pytania',
                'attr' => [
                    'rows' => 3,
                    'cols' => 80,
                ],
            ])
            ->add('deliveryType', EntityType::class, [
                'choices' => $options['delivery_types'],
                'class' => 'OrderBundle:DeliveryType',
                'expanded' => true,
                'label' => 'Sposób dostawy',
                'multiple' => false,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Dalej',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(['data_class' => Order::class])
            ->setRequired(['delivery_types']);
    }
}
