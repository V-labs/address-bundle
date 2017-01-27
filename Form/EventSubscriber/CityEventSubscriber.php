<?php

namespace Vlabs\AddressBundle\Form\EventSubscriber;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Vlabs\AddressBundle\Entity\Address;
use Vlabs\AddressBundle\Entity\City;

class CityEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var EntityRepository
     */
    private $cityRepo;

    /**
     * CityDataTransformer constructor.
     * @param EntityRepository $cityRepo
     */
    public function __construct(EntityRepository $cityRepo)
    {
        $this->cityRepo = $cityRepo;
    }

    public static function getSubscribedEvents()
    {
        return [
            FormEvents::POST_SET_DATA   => 'onPostSetData',
            FormEvents::PRE_SUBMIT      => 'onPreSubmit',
        ];
    }

    /**
     * @param FormEvent $event
     */
    public function onPostSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        if($data instanceof Address && $data->getCity())
        {
            $form->add('city', EntityType::class, [
                'choices' => [$data->getCity()],
                'class' => City::class,
                'choice_label' => function(City $city){
                    return sprintf('%s (%s)', $city->getZipCode(), $city->getName());
                }
            ]);
        }
    }

    /**
     * @param FormEvent $event
     */
    public function onPreSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        if(isset($data['city']))
        {
            $form->add('city', EntityType::class, [
                'choices' => [$this->cityRepo->find($data['city'])],
                'class' => City::class,
            ]);
        }
    }
}