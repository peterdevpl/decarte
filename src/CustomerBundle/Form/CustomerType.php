<?php

namespace CustomerBundle\Form;

use CustomerBundle\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class, ['label' => 'Imię i nazwisko'])
            ->add('street', TextType::class, ['label' => 'Adres (ulica lub miejscowość, numer)'])
            ->add('postalCode', TextType::class, [
                'label' => 'Kod pocztowy',
                'attributes' => [
                    'size' => 6,
                ],
            ])
            ->add('city', TextType::class, ['label' => 'Miasto'])
            ->add('email', EmailType::class, ['label' => 'E-mail'])
            ->add('phone', TextType::class, ['label' => 'Numer telefonu']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}
