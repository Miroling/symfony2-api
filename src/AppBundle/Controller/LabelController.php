<?php
/**
 * Created by PhpStorm.
 * User: miroling
 * Date: 17.04.15
 * Time: 12:23
 */

namespace AppBundle\Controller;


use AppBundle\DTO\Filter\Paginator;
use AppBundle\DTO\Label;
use AppBundle\Helper\CreateView;
use AppBundle\Helper\ModelHelper;
use Doctrine\DBAL\Driver\Connection;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class LabelController
 * @package AppBundle\Controller
 */
class LabelController extends FOSRestController {

    use CreateView;

    /**
     * Get all labels
     *
     * @ApiDoc(
     *      input = {
     *          "class" = "AppBundle\DTO\Filter\Paginator",
     *      },
     *      output = {
     *        "class" = "AppBundle\DTO\Label",
     *        "groups" = {"api_v1_label_get"},
     *        "collection" = true
     *      }
     * )
     *
     * @Rest\View()
     * @Rest\Route("")
     * @ParamConverter("paginator", converter="fos_rest.request_body")
     *
     * @param Paginator $paginator
     *
     * @return View
     */
    public function getAction(Paginator $paginator)
    {

        $request = $this->get('request');
        $paginator->setProperties($request->query->all());

        $conn = $this->get("database_connection");

        $offset = ($paginator->pageNumber - 1) * $paginator->limit;
        $qb = $conn->createQueryBuilder();
        $qb->select("*")->from("labels", "l")
           ->setFirstResult($offset)
           ->setMaxResults($paginator->limit);
        $labels =  $conn->executeQuery($qb->getSQL())->fetchAll();
        $labels = ModelHelper::fillCollection("\AppBundle\DTO\Label", $labels);

        return $this->createView($labels, array("api_v1_label_get"), Codes::HTTP_OK);
    }


    /**
     * Add label
     *
     * @ApiDoc(
     *      input = {
     *          "class" = "AppBundle\DTO\Label",
     *          "groups" = {"api_v1_label_post_in"}
     *      },
     *      output = {
     *          "class" = "AppBundle\DTO\Label",
     *          "groups" = {"api_v1_label_post_out"}
     *      },
     *      statusCodes={
     *         200="The request has succeeded",
     *         400="Validation error",
     *         500="Internal Server Error"
     *      }
     * )
     *
     * @Rest\View()
     * @Rest\Route("")
     *
     * @ParamConverter("label", converter="fos_rest.request_body")
     * @return View
     */
    public function postAction(Label $label, ConstraintViolationListInterface $validationErrors){

        if(count($validationErrors) > 0) {
            $view = $this->createView(array('errors' => $validationErrors), array(), Codes::HTTP_BAD_REQUEST);
        } else {

            $conn = $this->get("database_connection");

            if ($label->created instanceof \DateTime){
                $label->created = $label->created->format("Y-m-d H:i:s");
            }


            if ($conn->insert("labels", (array)$label)){

                $qb =  $conn->createQueryBuilder();
                $qb->select("*")->from("labels", "l")->where("id = :id");
                $label =  $conn->executeQuery($qb->getSQL(), array("id" => $conn->lastInsertId()))->fetch();
                // $label = ModelHelper::setProperties(new Label(), $label);

                $view = $this->createView($label, array("api_v1_label_post_out"), Codes::HTTP_OK);

            }

        }

        return $view;

    }

    /**
     * Delete label
     *
     * @ApiDoc()
     *
     * @param $labelId Label id.
     * @return View
     */
    public function deleteAction($labelId){

        $conn = $this->get("database_connection");
        $conn->delete("labels", array("id" => $labelId));
        return $this->createView(array(), array(), Codes::HTTP_NO_CONTENT);

    }

    /**
     * Update label
     *
     * @ApiDoc(
     *      input = {
     *          "class" = "AppBundle\DTO\Label",
     *          "groups" = {"api_v1_label_post_in"}
     *      },
     *      output = {
     *          "class" = "AppBundle\DTO\Label",
     *          "groups" = {"api_v1_label_post_out"}
     *      },
     * )
     *
     * @ParamConverter("label", converter="fos_rest.request_body")
     * @return View
     *
     * @param $id
     */
    public function putAction($labelId, Label $label, ConstraintViolationListInterface $validationErrors){

        if(count($validationErrors) > 0) {
            $view = $this->createView(array('errors' => $validationErrors), array(), Codes::HTTP_BAD_REQUEST);
        } else {

            $conn = $this->get("database_connection");

            if ($label->created instanceof \DateTime){
                $label->created = $label->created->format("Y-m-d H:i:s");
            }

            $label->id = $labelId;

            if ($conn->update("labels", (array)$label, array("id" => (int)$labelId))){

                $qb =  $conn->createQueryBuilder();
                $qb->select("*")->from("labels", "l")->where("id = :id");
                $label =  $conn->executeQuery($qb->getSQL(), array("id" => $labelId))->fetch();

                $label = ModelHelper::setProperties(new Label(), $label);
            }

            $view = $this->createView($label, array("api_v1_label_post_out"), Codes::HTTP_OK);

        }

        return $view;
    }

}