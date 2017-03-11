<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class ImageFileType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(['compound' => true])
            ->setRequired(['images', 'default_image']);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach ($options['images'] as $imageName => $imageOptions) {
            $builder->add($imageName . 'Name', HiddenType::class);
        }

        $builder->add('image', FileType::class, [
            'label' => false,
            'required' => false,
            'mapped' => false,
        ]);
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $default = $options['default_image'];
        $fileName = $form->get($default . 'Name')->getData();
        $view->vars['url'] = $fileName ? $options['images'][$default]['url'] . DIRECTORY_SEPARATOR . $fileName : null;
        $view->children['image']->vars = array_replace($view->vars, $view->children['image']->vars);
    }
}
