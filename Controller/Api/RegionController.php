<?php

namespace Vlabs\AddressBundle\Controller\Api;

use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Vlabs\AddressBundle\DTO\RegionListDTO;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Vlabs\AddressBundle\Entity\Region;

/**
 * Class RegionController
 * @package Vlabs\AddressBundle\Controller\Api
 */
class RegionController extends FOSRestController
{
    /**
     * @SWG\Get(
     *      tags = {"Address"},
     *      summary = "Get all regions",
     *      description = "Return a list of all regions.",
     *      responses = {
     *          @SWG\Response(
     *              response = 200,
     *              description = "Returned if successful",
     *              schema = @SWG\Schema(
     *                  type = "object",
     *                  ref = @Model(
     *                      type = RegionListDTO::class,
     *                      groups = {"address"}
     *                  )
     *              )
     *          ),
     *          @SWG\Response(
     *              response = 204,
     *              description = "Returned if no regions"
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
     * @return View
     */
    public function getRegionsAction()
    {
        $regionRepo = $this->getDoctrine()->getRepository(Region::class);
        $regions = $regionRepo->findBy([], ['name' => 'ASC']);

        if(empty($regions)){
            return $this->view(null, Response::HTTP_NO_CONTENT);
        }

        $regionList = (new RegionListDTO())->fillFromArray($regions);

        return $this->view($regionList, Response::HTTP_OK)
            ->setContext((new Context())->setGroups(['address']));
    }
}
