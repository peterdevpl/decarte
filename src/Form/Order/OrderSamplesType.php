<?php

declare(strict_types=1);

namespace Decarte\Shop\Form\Order;

use Decarte\Shop\Entity\Order\Samples\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderSamplesType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(['data_class' => Order::class])
            ->setRequired(['products']);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('items', CollectionType::class, [
                'label' => false,
                'entry_type' => SampleType::class,
                'entry_options' => [
                    'label' => false,
                    'products' => $options['products'],
                ],
            ])
            ->add('notes', TextType::class, ['label' => 'Uwagi', 'required' => false])
            ->add('email', EmailType::class, ['label' => 'E-mail'])
            ->add('name', TextType::class, ['label' => 'Imię i nazwisko'])
            ->add('address', TextType::class, ['label' => 'Ulica, nr domu i mieszkania'])
            ->add('postal_code', TextType::class, ['label' => 'Kod pocztowy'])
            ->add('city', TextType::class, ['label' => 'Miasto'])
            ->add('submit', SubmitType::class, [
                'label' => 'Zamów próbki',
                'attr' => ['class' => 'btn btn-primary'],
            ]);
    }
}
