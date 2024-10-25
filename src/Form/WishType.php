<?php

namespace App\Form;

use App\Entity\Wish;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WishType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class,[
                'label' => 'Your Idea',
            ])
            ->add('description',TextareaType::class,[
                'label' => 'Please describe it!',
                'required' => false,
            ])
            ->add('author',TextType::class,[
                'label' => 'Your username',
            ])
            ->add('published',checkboxType::class,[
                'label' => 'Published',
                'required' => false,
            ])
           /* ->add('dateCreated', null, [
                'widget' => 'single_text',
            ])
            ->add('dateUpdated', null, [
                'widget' => 'single_text',
            ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Wish::class,
        ]);
    }
}
