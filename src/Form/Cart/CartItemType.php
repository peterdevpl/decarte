<?php

namespace Decarte\Shop\Form\Cart;

use Decarte\Shop\Entity\Order\OrderItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CartItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
            /** @var OrderItem $orderItem */
            $orderItem = $event->getData();
            $form = $event->getForm();
            $minimumQuantity = $orderItem->getProduct()->getMinimumQuantity();

            $form
                ->add('quantity', IntegerType::class, [
                    'label' => 'Liczba sztuk' . ($minimumQuantity > 1 ? ' (minimum ' . $minimumQuantity . ')' : ''),
                    'attr' => [
                        'min' => $minimumQuantity,
                    ],
                ]);
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(['data_class' => OrderItem::class]);
    }

    public function getBlockPrefix()
    {
        return 'cart_item';
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        /** @var OrderItem $orderItem */
        $orderItem = $form->getData();
        $product = $orderItem->getProduct();

        $view->vars['item'] = $orderItem;
        $view->vars['product'] = $product;
    }
}
