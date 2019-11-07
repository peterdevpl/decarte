<?php

declare(strict_types=1);

namespace Decarte\Shop\Form\Cart;

use Decarte\Shop\Entity\Order\DeliveryType;
use Decarte\Shop\Entity\Order\Order;
use Decarte\Shop\Entity\Order\RealizationType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CartType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('items', CollectionType::class, [
                'label' => false,
                'entry_type' => CartItemType::class,
                'entry_options' => [
                    'label' => false,
                ],
            ])
            ->add('deliveryType', EntityType::class, [
                'choices' => $options['delivery_types'],
                'class' => DeliveryType::class,
                'expanded' => true,
                'label' => 'form.deliveryType',
                'choice_label' => function (DeliveryType $item) {
                    return $item->getName() . ' (' . ($item->getPrice() / 100) . ' PLN)';
                },
                'multiple' => false,
            ])
            ->add('realizationType', EntityType::class, [
                'choices' => $options['realization_types'],
                'class' => RealizationType::class,
                'expanded' => true,
                'label' => 'form.realizationType',
                'choice_label' => function (RealizationType $item) {
                    $description = $item->getName() . ' - ' . $item->getDeliveryDays() .
                        ($item->getDeliveryDays() > 4 ? ' dni roboczych' : ' dni robocze');
                    if ($item->getPrice() > 0) {
                        $description .= ' (+' . ($item->getPrice() / 100) . ' PLN)';
                    }

                    return $description;
                },
                'multiple' => false,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'cart.calculateAndSave',
            ])
            ->add('save_and_order', SubmitType::class, [
                'label' => 'cart.placeOrder',
                'attr' => ['class' => 'btn btn-primary'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(['data_class' => Order::class])
            ->setRequired(['realization_types', 'delivery_types']);
    }
}
