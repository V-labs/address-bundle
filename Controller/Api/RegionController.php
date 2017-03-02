<?php

namespace Vlabs\AddressBundle\Controller\Api;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Vlabs\AddressBundle\DTO\RegionListDTO;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Vlabs\AddressBundle\Entity\Region;

class RegionController extends FOSRestController
{
    /**
     * @ApiDoc(
     *  section="Vlabs",
     *  description="Get the list of regions",
     *  output={
     *   "class" = "Vlabs\AddressBundle\DTO\RegionListDTO"
     *  },
     *  statusCodes={
     *      200 = "Returned if successful",
     *      204 = "Returned if there is no regions"
     *  }
     * )
     *
     * @return \FOS\RestBundle\View\View
     */
    public function getRegionsAction()
    {
        $regionRepo = $this->getDoctrine()->getRepository(Region::class);
        $regions = $regionRepo->findBy([], ['name' => 'ASC']);

        if(empty($regions)){
            return $this->view(null, Response::HTTP_NO_CONTENT);
        }

        $regionList = (new RegionListDTO())->fillFromArray($regions);

        return $this->view($regionList, Response::HTTP_OK);
    }
}
