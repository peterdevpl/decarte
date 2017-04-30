<?php

namespace AppBundle\Form;

use AppBundle\Entity\SamplesOrder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderSamplesType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(['data_class' => SamplesOrder::class])
            ->setRequired(['products']);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('items', CollectionType::class, [
                'label' => false,
                'entry_type' => SampleType::class,
                'entry_options' => [
                    'label' => 'Próbka',
                    'products' => $options['products'],
                ],
            ])
            ->add('notes', TextareaType::class, ['label' => 'Uwagi', 'required' => false, 'attr' => ['cols' => 40, 'rows' => 4]])
            ->add('email', EmailType::class, ['label' => 'E-mail', 'attr' => ['size' => 50]])
            ->add('name', TextType::class, ['label' => 'Imię i nazwisko', 'attr' => ['size' => 50]])
            ->add('address', TextType::class, ['label' => 'Adres', 'attr' => ['size' => 50]])
            ->add('postal_code', TextType::class, ['label' => 'Kod pocztowy', 'attr' => ['size' => 6]])
            ->add('city', TextType::class, ['label' => 'Miasto', 'attr' => ['size' => 50]])
            ->add('submit', SubmitType::class, ['label' => 'Zamów']);
    }
}
