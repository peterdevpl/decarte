<?php

declare(strict_types=1);

namespace Decarte\Shop\Form\Order;

use Decarte\Shop\Entity\Order\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ShippingDetailsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, ['label' => 'form.name']);

        /** @var Order $order */
        $order = $builder->getData();
        if ($order->hasShippingAddress()) {
            $builder
                ->add('street', TextType::class, ['label' => 'form.address'])
                ->add('postalCode', TextType::class, ['label' => 'form.zipcode'])
                ->add('city', TextType::class, ['label' => 'form.city']);
        }

        $builder
            ->add('email', EmailType::class, ['label' => 'form.email'])
            ->add('phone', TextType::class, ['label' => 'form.phone'])
            ->add('notes', TextareaType::class, [
                'label' => 'form.notes_and_questions',
                'required' => false,
                'attr' => [
                    'rows' => 3,
                    'cols' => 80,
                ],
            ])
            ->add('hasInvoice', CheckboxType::class, [
                'label' => 'form.invoice',
                'data' => (bool) \trim((string) $order->getTaxId()),
                'mapped' => false,
                'required' => false,
            ])
            ->add('taxId', TextType::class, ['label' => 'form.tax_id', 'required' => false])
            ->add('save', SubmitType::class, [
                'label' => 'form.next',
                'attr' => ['class' => 'btn btn-primary'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(['data_class' => Order::class]);
    }
}
