<?php

namespace RestaurantBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('prix')
            ->add('ordre')
            ->add('plats', EntityType::class, array(
                'class' => 'RestaurantBundle:Plat',
                'choice_label' => 'titre',
                'multiple' => true,
                'expanded' => true,
                'required'=> false
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
            'data_class' => 'RestaurantBundle\Entity\Menu'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'restaurantbundle_menu';
    }


}
