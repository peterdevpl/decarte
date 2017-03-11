<?php
namespace AppBundle\Form;

use AppBundle\Form\Type\StringImageFileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductSeriesForm extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['images', 'default_image']);
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('productCollection', EntityType::class, [
                'class' => 'AppBundle:ProductCollection',
                'choices' => $builder->getData()->getProductCollection()->getProductType()->getProductCollections(),
                'label' => 'Kolekcja',
            ])
            ->add('name', TextType::class, ['label' => 'Nazwa (tylko dla CMS)'])
            ->add('isVisible', CheckboxType::class, ['label' => 'Seria widoczna na stronie', 'required' => false])
            ->add('description', TextareaType::class, ['label' => 'Opis', 'required' => false])
            ->add('imageName', StringImageFileType::class, [
                'label' => 'Zdjęcie serii',
                'required' => false,
                'images' => $options['images'],
                'default_image' => $options['default_image'],
            ])
            ->add('save', SubmitType::class, ['label' => 'Zapisz serię'])
            ->addEventSubscriber(new ProductSeriesFormListener($options));
    }
}
