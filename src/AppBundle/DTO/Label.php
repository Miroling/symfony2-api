<?php
/**
 * Created by PhpStorm.
 * User: miroling
 * Date: 17.04.15
 * Time: 12:32
 */

namespace AppBundle\DTO;

/**
 * Class Label
 * @package AppBundle\DTO
 */
class Label {

    /**
     * Label id.
     *
     * @var integer
     */
    public $id;

    /**
     * Description.
     *
     * @var string
     */
    public $description;

    /**
     * Name
     *
     * @var string
     */
    public $name;

    /**
     * Latitude
     *
     * @var float
     */
    public $lat;

    /**
     * Longitude.
     *
     * @var float
     */
    public $lng;

//    /**
//     * Place photos
//     *
//     * @var array
//     */
//    public $photos;

    /**
     * Label add date.
     *
     * @var \DateTime
     */
    public $created;

}