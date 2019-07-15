<?php

namespace Vlabs\AddressBundle\Controller\Api;

use FOS\RestBundle\Context\Context;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Tbbc\RestUtilBundle\Error\Exception\FormErrorException;
use Vlabs\AddressBundle\DTO\CityListDTO;
use Symfony\Component\HttpFoundation\Response;
use Vlabs\AddressBundle\Entity\City;
use Vlabs\AddressBundle\Form\Type\CityType;

/**
 * Class CityController
 * @package Vlabs\AddressBundle\Controller\Api
 */
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

    /**
     * @ApiDoc(
     *  section="Vlabs",
     *  description="Create a city",
     *  input="Vlabs\AddressBundle\Form\Type\CityType",
     *  statusCodes={
     *      201 = "Returned if created",
     *      400 = "Returned if the request failed"
     *  }
     * )
     *
     * @return \FOS\RestBundle\View\View
     */
    public function postCityAction(Request $request)
    {
        $city = new City();

        $form = $this->createForm(CityType::class, $city);

        $form->submit($request->request->all());

        if ($form->isSubmitted() && !$form->isValid()) {
            throw new FormErrorException($form);
        }

        $cityRepository = $this->get('doctrine.orm.entity_manager')
            ->getRepository('VlabsAddressBundle:City');

        $cityRepository->save($city);

        return $this->view(null, Response::HTTP_CREATED);
    }

    /**
     * @ApiDoc(
     *  section="Vlabs",
     *  description="Update a city",
     *  input="Vlabs\AddressBundle\Form\Type\CityType",
     *  statusCodes={
     *      201 = "Returned if updated",
     *      400 = "Returned if the request failed"
     *  }
     * )
     *
     * @ParamConverter("city", options={
     *     "mapping": {
     *          "city_id": "id"
     *      }
     * })
     *
     * @return \FOS\RestBundle\View\View
     */
    public function putCityAction(Request $request, City $city)
    {
        $form = $this->createForm(CityType::class, $city);

        $form->submit($request->request->all());

        if ($form->isSubmitted() && !$form->isValid()) {
            throw new FormErrorException($form);
        }

        $cityRepository = $this->get('doctrine.orm.entity_manager')
            ->getRepository('VlabsAddressBundle:City');

        $cityRepository->remove($city);

        return $this->view(null, Response::HTTP_OK);
    }

    /**
     * @ApiDoc(
     *  section="Vlabs",
     *  description="Delete a city",
     *  statusCodes={
     *      200 = "Returned if successful",
     *      409 = "Returned if city is not deletable"
     *  }
     * )
     *
     * @ParamConverter("city", options={
     *     "mapping": {
     *          "city_id": "id"
     *      }
     * })
     *
     * @param Request $request
     * @param City    $city
     *
     * @return \FOS\RestBundle\View\View
     */
    public function deleteCityAction(Request $request, City $city): View
    {
        $cityRepository = $this->get('doctrine.orm.entity_manager')
            ->getRepository('VlabsAddressBundle:City');

        $cityRepository->remove($city);

        return $this->view(null, Response::HTTP_OK);
    }
}
