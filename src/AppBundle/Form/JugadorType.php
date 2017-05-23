<?php
/**
 * Created by PhpStorm.
 * User: albertau
 * Date: 28/02/17
 * Time: 19:16
 */

namespace AppBundle\Form;


use\AppBundle\Entity\Jugador;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class JugadorType extends  AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class)
            ->add('apellidos', TextType::class)
            ->add('numero', IntegerType::class)
            ->add('posicion', TextType::class)


        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Jugador::class
            ]
        );
    }
    public function getName()
    {
        return 'app_bundle_jugador_type';
    }
}