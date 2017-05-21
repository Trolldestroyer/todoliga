<?php
/**
 * Created by PhpStorm.
 * User: albertau
 * Date: 28/02/17
 * Time: 19:16
 */

namespace AppBundle\Form;


use\AppBundle\Entity\Equipo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class EquipoType extends  AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class)
            ->add('texto', TextType::class)
            ->add('localizacion', TextType::class)
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Equipo::class
            ]
        );
    }
    public function getName()
    {
        return 'app_bundle_equipo_type';
    }
}