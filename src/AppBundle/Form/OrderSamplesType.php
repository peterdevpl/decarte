<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class OrderSamplesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('samples', CollectionType::class, [
                'label' => false,
                'entry_type' => SampleType::class,
            ])
            ->add('notes', TextareaType::class, ['label' => 'Uwagi', 'required' => false, 'mapped' => false, 'attr' => ['cols' => 40, 'rows' => 4]])
            ->add('email', TextType::class, ['label' => 'E-mail', 'mapped' => false, 'attr' => ['size' => 50]])
            ->add('name', TextType::class, ['label' => 'Imię i nazwisko', 'mapped' => false, 'attr' => ['size' => 50]])
            ->add('address', TextType::class, ['label' => 'Adres', 'mapped' => false, 'attr' => ['size' => 50]])
            ->add('postal_code', TextType::class, ['label' => 'Kod pocztowy', 'mapped' => false, 'attr' => ['size' => 6]])
            ->add('city', TextType::class, ['label' => 'Miasto', 'mapped' => false, 'attr' => ['size' => 50]])
            ->add('submit', SubmitType::class, ['label' => 'Zamów']);
    }
}
