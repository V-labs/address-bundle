<?php

namespace Vlabs\AddressBundle\Controller\Api;

use FOS\RestBundle\Context\Context;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Tbbc\RestUtilBundle\Error\Exception\FormErrorException;
use Vlabs\AddressBundle\DTO\CityDTO;
use Vlabs\AddressBundle\DTO\CityListDTO;
use Symfony\Component\HttpFoundation\Response;
use Vlabs\AddressBundle\Entity\City;
use Vlabs\AddressBundle\Form\Type\CityType;
use Swagger\Annotations as SWG;

/**
 * Class CityController
 * @package Vlabs\AddressBundle\Controller\Api
 */
class CityController extends FOSRestController
{
    /**
     * @SWG\Get(
     *      tags = {"Address"},
     *      summary = "Get cities",
     *      description = "Return a list of all cities filtered by zipCode.",
     *      responses = {
     *          @SWG\Response(
     *              response = 200,
     *              description = "Returned if successful",
     *              schema = @SWG\Schema(
     *                  type = "object",
     *                  ref = @Model(
     *                      type = CityListDTO::class,
     *                      groups = {"address"}
     *                  )
     *              )
     *          )
     *      },
     *      parameters = {
     *          @SWG\Parameter(
     *              name = "Accept",
     *              in = "header",
     *              required = true,
     *              type = "string",
     *              default = "application/json"
     *          ),
     *          @SWG\Parameter(
     *              name = "Content-Type",
     *              in = "header",
     *              required = true,
     *              type = "string",
     *              default = "application/json"
     *          ),
     *          @SWG\Parameter(
     *              name = "q",
     *              in = "query",
     *              required = false,
     *              type = "string",
     *              description = "The searched city"
     *          )
     *      }
     * )
     *
     * @return View
     */
    public function getCitiesAction(Request $request)
    {
        if (!$search = $request->query->get('q')) {
            return $this->view([], Response::HTTP_ACCEPTED);
        }

        $cityRepository = $this->get('doctrine.orm.entity_manager')
            ->getRepository('VlabsAddressBundle:City');

        $cities = $cityRepository->search($search);

        $cityList = (new CityListDTO())->fillFromArray($cities);

        return $this->view($cityList, Response::HTTP_ACCEPTED)
            ->setContext((new Context())->setGroups(['city']));
    }

    /**
     * @SWG\Get(
     *      tags = {"Address"},
     *      summary = "Get city",
     *      description = "Return a specific city.",
     *      responses = {
     *          @SWG\Response(
     *              response = 200,
     *              description = "Returned if successful",
     *              schema = @SWG\Schema(
     *                  type = "object",
     *                  ref = @Model(
     *                      type = CityDTO::class,
     *                      groups = {"address"}
     *                  )
     *              )
     *          )
     *      },
     *      parameters = {
     *          @SWG\Parameter(
     *              name = "Accept",
     *              in = "header",
     *              required = true,
     *              type = "string",
     *              default = "application/json"
     *          ),
     *          @SWG\Parameter(
     *              name = "Content-Type",
     *              in = "header",
     *              required = true,
     *              type = "string",
     *              default = "application/json"
     *          )
     *      }
     * )
     *
     * @ParamConverter("city", options={
     *     "mapping": {
     *          "city_id": "id"
     *      }
     * })
     *
     * @return View
     */
    public function getCityAction(Request $request, City $city)
    {
        $city = (new CityDTO())->fillFromEntity($city);

        return $this->view($city, Response::HTTP_OK)
            ->setContext((new Context())->setGroups(['city']));
    }

    /**
     * @SWG\Post(
     *      tags = {"Address"},
     *      summary = "Create a new city",
     *      description = "Create a new city.",
     *      responses = {
     *          @SWG\Response(
     *              response = 201,
     *              description = "Returned if created"
     *          ),
     *          @SWG\Response(
     *              response = 400,
     *              description = "Returned if an error occurred",
     *              examples = {
     *                  400101 = {
     *                      "http_status_code": 400,
     *                      "code": 400101,
     *                      "message": "Cette valeur n'est pas valide.",
     *                      "extended_message": {
     *                          "global_errors": {},
     *                          "property_errors": {
     *                              "field_name": {
     *                                  "Cette valeur n'est pas valide."
     *                              }
     *                          }
     *                      },
     *                      "more_info_url": null
     *                  }
     *              }
     *          )
     *      },
     *      parameters = {
     *          @SWG\Parameter(
     *              name = "Accept",
     *              in = "header",
     *              required = true,
     *              type = "string",
     *              default = "application/json"
     *          ),
     *          @SWG\Parameter(
     *              name = "Accept-Language",
     *              in = "header",
     *              required = true,
     *              type = "string",
     *              default = "fr"
     *          ),
     *          @SWG\Parameter(
     *              name = "Content-Type",
     *              in = "header",
     *              required = true,
     *              type = "string",
     *              default = "application/json"
     *          ),
     *          @SWG\Parameter(
     *              name="form",
     *              in="body",
     *              @Model(type=CityType::class)
     *          )
     *      }
     * )
     *
     * @param Request $request
     *
     * @return View
     */
    public function postCityAction(Request $request)
    {
        $city = new City();

        $form = $this->createForm(CityType::class, $city, [
            'csrf_protection' => false,
        ]);

        $form->submit($request->request->all());

        if (!$form->isSubmitted() || $form->isSubmitted() && !$form->isValid()) {
            $formError = $this->getParameter('vlabs_address.tbbc_error.form_error');
            throw new $formError($form);
        }

        $cityRepository = $this->get('doctrine.orm.entity_manager')
            ->getRepository('VlabsAddressBundle:City');

        $cityRepository->save($city);

        return $this->view(null, Response::HTTP_CREATED);
    }

    /**
     * @SWG\Put(
     *      tags = {"Address"},
     *      summary = "Update a city",
     *      description = "Update a city.",
     *      responses = {
     *          @SWG\Response(
     *              response = 200,
     *              description = "Returned if updated"
     *          ),
     *          @SWG\Response(
     *              response = 400,
     *              description = "Returned if an error occurred",
     *              examples = {
     *                  400101 = {
     *                      "http_status_code": 400,
     *                      "code": 400101,
     *                      "message": "Cette valeur n'est pas valide.",
     *                      "extended_message": {
     *                          "global_errors": {},
     *                          "property_errors": {
     *                              "field_name": {
     *                                  "Cette valeur n'est pas valide."
     *                              }
     *                          }
     *                      },
     *                      "more_info_url": null
     *                  }
     *              }
     *          )
     *      },
     *      parameters = {
     *          @SWG\Parameter(
     *              name = "Accept",
     *              in = "header",
     *              required = true,
     *              type = "string",
     *              default = "application/json"
     *          ),
     *          @SWG\Parameter(
     *              name = "Accept-Language",
     *              in = "header",
     *              required = true,
     *              type = "string",
     *              default = "fr"
     *          ),
     *          @SWG\Parameter(
     *              name = "Content-Type",
     *              in = "header",
     *              required = true,
     *              type = "string",
     *              default = "application/json"
     *          ),
     *          @SWG\Parameter(
     *              name="form",
     *              in="body",
     *              @Model(type=CityType::class)
     *          )
     *      }
     * )
     *
     * @ParamConverter("city", options={
     *     "mapping": {
     *          "city_id": "id"
     *      }
     * })
     *
     * @param Request $request
     * @param City $city
     *
     * @return View
     */
    public function putCityAction(Request $request, City $city)
    {
        $form = $this->createForm(CityType::class, $city, [
            'csrf_protection' => false,
        ]);

        $form->submit($request->request->all());

        if (!$form->isSubmitted() || $form->isSubmitted() && !$form->isValid()) {
            $formError = $this->getParameter('vlabs_address.tbbc_error.form_error');
            throw new $formError($form);
        }

        $cityRepository = $this->get('doctrine.orm.entity_manager')
            ->getRepository('VlabsAddressBundle:City');

        $cityRepository->save($city);

        return $this->view(null, Response::HTTP_OK);
    }

    /**
     * @SWG\Delete(
     *      tags = {"Address"},
     *      summary = "Delete a city",
     *      description = "Delete a city.",
     *      responses = {
     *          @SWG\Response(
     *              response = 200,
     *              description = "Returned if Delete"
     *          ),
     *          @SWG\Response(
     *              response = 409,
     *              description = "Returned if city cannot be deleted."
     *          )
     *      },
     *      parameters = {
     *          @SWG\Parameter(
     *              name = "Accept",
     *              in = "header",
     *              required = true,
     *              type = "string",
     *              default = "application/json"
     *          ),
     *          @SWG\Parameter(
     *              name = "Accept-Language",
     *              in = "header",
     *              required = true,
     *              type = "string",
     *              default = "fr"
     *          ),
     *          @SWG\Parameter(
     *              name = "Content-Type",
     *              in = "header",
     *              required = true,
     *              type = "string",
     *              default = "application/json"
     *          )
     *      }
     * )
     *
     * @ParamConverter("city", options={
     *     "mapping": {
     *          "city_id": "id"
     *      }
     * })
     *
     * @param Request $request
     * @param City $city
     *
     * @return View
     */
    public function deleteCityAction(Request $request, City $city)
    {
        $cityRepository = $this->get('doctrine.orm.entity_manager')
            ->getRepository('VlabsAddressBundle:City');

        $cityRepository->remove($city);

        return $this->view(null, Response::HTTP_OK);
    }
}
