<?php
/**
 * Created by PhpStorm.
 * User: miroling
 * Date: 17.04.15
 * Time: 12:32
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Label
 * @package AppBundle\Entity
 *
 * @ORM\Table(name="labels")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Label {

    /**
     * Label id.
     *
     * @var integer
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * Description.
     *
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    public $description;

    /**
     * Name
     *
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    public $name;

    /**
     * Latitude
     *
     * @var float
     * @ORM\Column(type="float")
     */
    public $lat;

    /**
     * Longitude.
     *
     * @var float
     * @ORM\Column(type="float")
     */
    public $lng;

    /**
     * Place photos
     *
     * @var array
     * @ORM\Column(type="simple_array", nullable=true)
     */
    public $photos;

    /**
     * Label add date.
     *
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    public $created;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return float
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * @param float $lat
     */
    public function setLat($lat)
    {
        $this->lat = $lat;
    }

    /**
     * @return float
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * @param float $lng
     */
    public function setLng($lng)
    {
        $this->lng = $lng;
    }

    /**
     * @return array
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * @param array $photos
     */
    public function setPhotos($photos)
    {
        $this->photos = $photos;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->created= new \DateTime();
    }

}