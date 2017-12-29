<?php

namespace OrderBundle\Form;

use OrderBundle\Entity\SamplesOrderItem;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SampleType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(['compound' => true, 'data_class' => SamplesOrderItem::class])
            ->setRequired(['products']);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product', EntityType::class, [
                'class' => 'ProductBundle:Product',
                'choices' => $options['products'],
                'label' => 'model',
                'placeholder' => '--',
                'required' => false,
            ]);
    }
}
