<?php

namespace Vlabs\AddressBundle\Controller\Api;

use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Vlabs\AddressBundle\DTO\DepartmentListDTO;
use Vlabs\AddressBundle\Entity\Department;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

/**
 * Class DepartmentController
 * @package Vlabs\AddressBundle\Controller\Api
 */
class DepartmentController extends FOSRestController
{
    /**
     * @SWG\Get(
     *      tags = {"Address"},
     *      summary = "Get all departments",
     *      description = "Return a list of all departments.",
     *      responses = {
     *          @SWG\Response(
     *              response = 200,
     *              description = "Returned if successful",
     *              schema = @SWG\Schema(
     *                  type = "object",
     *                  ref = @Model(
     *                      type = DepartmentListDTO::class,
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
     * @return View
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
            ->setContext((new Context())->setGroups(['department']));
    }
}
