<?php

namespace Vlabs\AddressBundle\Controller\Api;

use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Vlabs\AddressBundle\DTO\CityListDTO;
use Symfony\Component\HttpFoundation\Response;

class CityController extends FOSRestController
{
    /**
     * @ApiDoc(
     *  section="Vlabs",
     *  description="Get the list of cities",
     *  output={
     *   "class" = "Vlabs\AddressBundle\DTO\CityListDTO",
     *   "groups" = {"address"}
     *  },
     *  statusCodes={
     *      202 = "Returned if successful"
     *  }
     * )
     *
     * @return \FOS\RestBundle\View\View
     */
    public function getCitiesAction(Request $request)
    {
        if (!$search = $request->query->get('q')) {
            return $this->view([], Response::HTTP_ACCEPTED);
        }

        $cityRepository = $this->get('doctrine.orm.entity_manager')
            ->getRepository('VlabsAddressBundle:City');

        $cities = $cityRepository->searchByZipCode($search);

        $cityList = (new CityListDTO())->fillFromArray($cities);

        return $this->view($cityList, Response::HTTP_ACCEPTED)
            ->setContext((new Context())->setGroups(['address']));
    }
}
