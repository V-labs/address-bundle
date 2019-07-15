<?php
namespace Vlabs\AddressBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vlabs\AddressBundle\Entity\City;
use Vlabs\AddressBundle\Entity\Department;

/**
 * Class CityType
 * @package Application\Domain\User\Form\Type
 */
class CityType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true
            ])
            ->add('zip_code', TextType::class, [
                'property_path' => 'zipCode',
                'required'      => true
            ])
            ->add('department', EntityType::class, [
                'class'         => Department::class,
                'required'      => true,
                'choice_label'  => function (Department $department) {
                    return sprintf('%s (%s)', $department->getName(), $department->getCode());
                }
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => City::class
        ]);
    }

    /**
     * @return null
     */
    public function getName()
    {
        return null;
    }

    /**
     * @return null
     */
    public function getBlockPrefix()
    {
        return null;
    }
}