<?php

namespace Vlabs\AddressBundle\Controller\Api;

use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Vlabs\AddressBundle\DTO\DepartmentListDTO;
use Vlabs\AddressBundle\Entity\Department;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class DepartmentController extends FOSRestController
{
    /**
     * @ApiDoc(
     *  section="Vlabs",
     *  description="Get the list of departments",
     *  output={
     *   "class" = "Vlabs\AddressBundle\DTO\DepartmentListDTO",
     *   "groups" = {"address"}
     *  },
     *  statusCodes={
     *      200 = "Returned if successful",
     *      204 = "Returned if there is no department"
     *  }
     * )
     *
     * @return \FOS\RestBundle\View\View
     */
    public function getDepartmentsAction()
    {
        $departmentRepo = $this->getDoctrine()->getRepository(Department::class);
        $departments = $departmentRepo->findBy([], ['code' => 'ASC']);

        if(empty($departments)){
            return $this->view(null, Response::HTTP_NO_CONTENT);
        }

        $departmentList = (new DepartmentListDTO())->fillFromArray($departments);

        return $this->view($departmentList, Response::HTTP_OK)
            ->setContext((new Context())->setGroups(['address']));
    }
}
