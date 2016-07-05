<?php

namespace Vlabs\AddressBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use Symfony\Component\HttpFoundation\Request;
use Vlabs\AddressBundle\DTO\CityListDTO;

class CityController extends FOSRestController
{
    public function getCitiesAction(Request $request)
    {
        if (!$search = $request->query->get('q')) {
            return $this->view([], Codes::HTTP_ACCEPTED);
        }

        $cityRepository = $this->get('doctrine.orm.entity_manager')
            ->getRepository('VlabsAddressBundle:City');

        $cities = $cityRepository->searchByZipCode($search);

        $cityList = (new CityListDTO())->fillFromArray($cities);

        return $this->view($cityList, Codes::HTTP_ACCEPTED);
    }
}
