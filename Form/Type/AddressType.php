<?php

namespace Vlabs\AddressBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vlabs\AddressBundle\Entity\Address;
use Vlabs\AddressBundle\Entity\City;
use Vlabs\AddressBundle\Entity\Country;
use Vlabs\AddressBundle\Form\CityDataTransformer;
use Vlabs\AddressBundle\Form\EventSubscriber\CityEventSubscriber;

class AddressType extends AbstractType
{
    /**
     * @var EntityRepository
     */
    private $cityRepo;

    /**
     * AddressType constructor.
     * @param EntityRepository $cityRepo
     */
    public function __construct(EntityRepository $cityRepo)
    {
        $this->cityRepo = $cityRepo;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('street', TextType::class)
            ->add('street2', TextType::class, [
                'required' => false
            ])
            ->add('city', EntityType::class, [
                'choices' => [],
                'class' => City::class
            ])
        ;

        $builder->addEventSubscriber(new CityEventSubscriber($this->cityRepo));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Address::class
        ]);
    }
}