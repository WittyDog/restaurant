<?php

namespace RestaurantBundle\Form;

use RestaurantBundle\Entity\Plat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlatType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $categories = [
            "entrée"    => "entrée",
            "plat"      => "plat",
            "dessert"   => "dessert"
        ];

        $allergenes = [
            "crustacés"         => "crustacés",
            "arachides"         => "arachides",
            "lait"              => "lait",
            "fruits à coques"   => "fruits à coques"
        ];

        $builder
            ->add('titre')
            ->add('description')
            ->add('prix')
            ->add('faitMaison')
            ->add('image', FileType::class, array(
                'required'=> false
            ))
            ->add('categorie', ChoiceType::class, array(
                'choices' => $categories,
                'multiple'=> false,
                'expanded'=> true,
                'required'=> false
            ))
            ->add('allergenes', ChoiceType::class, array(
                'choices' => $allergenes,
                'multiple'=> true,
                'expanded'=> true
            ))
            ->add('statut', ChoiceType::class, array(
                'choices' => array(
                    'brouillon' => 'brouillon',
                    'en validation' => 'en validation'
                ),
                'multiple'=> false,
                'required'=> true,
                'expanded' => true
            ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Plat::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'restaurantbundle_plat';
    }


}
