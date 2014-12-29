<?php

namespace Common\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Country
 *
 * @ORM\Table(name="country")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Country extends BaseEntity
{

    const PUBLISHED = 'yes';
    const UNPUBLISHED = 'no';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", length=255, type="string")
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="english_name", length=255, type="string")
     */
    protected $englishName;

    /**
     * @var string
     *
     * @ORM\Column(name="short_name", length=50, type="string")
     */
    protected $shortName;

    /**
     * @var string
     *
     * @ORM\Column(name="published", length=3, type="string")
     */
    protected $published;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated", type="datetime", nullable=true)
     */
    protected $updated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=true)
     */
    protected $created;

    /**
     * @var City
     *
     * @ORM\OneToMany(targetEntity="City", mappedBy="country", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="id", referencedColumnName="id_country")
     */
    protected $cities;

    public function __construct()
    {
        $this->cities = new ArrayCollection();
    }

    /**
     * @ORM\PrePersist
     */
    public function insert()
    {
        $this->created = new \DateTime();
        $this->updated = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function update()
    {
        $this->updated = new \DateTime();
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
    public function getEnglishName()
    {
        return $this->englishName;
    }

    /**
     * @param string $englishName
     *
     * @return $this
     */
    public function setEnglishName($englishName)
    {
        $this->englishName = $englishName;

        return $this;
    }

    /**
     * @return string
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * @param string $shortName
     *
     * @return $this
     */
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;

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
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param \DateTime $updated
     *
     * @return $this
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

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
     * @return City
     */
    public function getCities()
    {
        return $this->cities;
    }

    /**
     * @param City $cities
     *
     * @return $this
     */
    public function setCities($cities)
    {
        $this->cities = $cities;

        return $this;
    }

    public function addCities($cities)
    {
        if ($cities instanceof Collection) {
            foreach($cities as $city){
                $this->cities[] = $city;
            }
        } else {
            $this->cities[] = $cities;
        }
    }

    public function removeCities($cities)
    {
        if ($cities instanceof Collection) {
            foreach($cities as $city){
                $this->cities->removeElement($city);
            }
        } else {
            $this->cities->removeElement($cities);
        }
    }


}
