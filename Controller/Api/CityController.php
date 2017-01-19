<?php

namespace Vlabs\AddressBundle\Controller\Api;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Vlabs\AddressBundle\DTO\CityListDTO;
use Symfony\Component\HttpFoundation\Response;

class CityController extends FOSRestController
{
    public function getCitiesAction(Request $request)
    {
        if (!$search = $request->query->get('q')) {
            return $this->view([], Response::HTTP_ACCEPTED);
        }

        $cityRepository = $this->get('doctrine.orm.entity_manager')
            ->getRepository('VlabsAddressBundle:City');

        $cities = $cityRepository->searchByZipCode($search);

        $cityList = (new CityListDTO())->fillFromArray($cities);

        return $this->view($cityList, Response::HTTP_ACCEPTED);
    }
}
