<?php

namespace Common\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Advertising place
 *
 * @ORM\Table(name="advertising_place")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class AdvertisingPlace extends BaseEntity
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="integer")
     */
    protected $type;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string")
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string")
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(name="published", length=3, type="string")
     */
    protected $published;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=true)
     */
    protected $created;

    /**
     * @var Advertising
     *
     * @ORM\OneToMany(targetEntity="Advertising", mappedBy="place", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="id", referencedColumnName="id_place")
     */
    protected $advertising;

    public function __construct()
    {
        $this->advertising = new ArrayCollection();
    }

    /**
     * @ORM\PrePersist
     */
    public function insert()
    {
        $this->created = new \DateTime();
        $this->type = 1;
    }

    public function addAdvertising($advertising)
    {
        if (is_array($advertising) || $advertising instanceof Collection){

            foreach($advertising as $advertise){
                $this->advertising[] = $advertise->setPlace($advertise);
            }

        } else {
            $this->advertising[] = $advertising->setPlace($this);
        }
    }

    public function removeAdvertising($advertising)
    {
        if (is_array($advertising) || $advertising instanceof Collection){
            foreach($advertising as $advertise){
                $this->advertising->removeElement($advertise);
            }
        }else {
            $this->advertising->removeElement($advertising);
        }

        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param int $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
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
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * @param string $published
     *
     * @return $this
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
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
     *
     * @return $this
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @return Advertising
     */
    public function getAdvertising()
    {
        return $this->advertising;
    }

    /**
     * @param Advertising $advertising
     *
     * @return $this
     */
    public function setAdvertising($advertising)
    {
        $this->advertising = $advertising;

        return $this;
    }

}