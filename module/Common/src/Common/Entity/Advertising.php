<?php

namespace Common\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Advertising
 *
 * @ORM\Table(name="advertising")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Advertising extends BaseEntity
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
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var AdvertisingPlace
     *
     * @ORM\ManyToOne(targetEntity="AdvertisingPlace", inversedBy="advertising")
     * @ORM\JoinColumn(name="id_place", referencedColumnName="id")
     */
    protected $place;

    /**
     * @var Asset
     *
     * @ORM\OneToOne(targetEntity="Asset", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="image", referencedColumnName="id")
     */
    protected $image;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="integer")
     */
    protected $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="count", type="integer")
     */
    protected $count;

    /**
     * @var integer
     *
     * @ORM\Column(name="counter", type="integer")
     */
    protected $counter;

    /**
     * @var string
     *
     * @ORM\Column(name="published", length=3, type="string")
     */
    protected $published;

    /**
     * @var string
     *
     * @ORM\Column(name="link", type="string")
     */
    protected $url;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AdvertisingTranslations", mappedBy="advertising", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="id", referencedColumnName="id_advertising")
     */
    protected $translations;

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

    public function __construct()
    {
        $this->translations = new ArrayCollection();
        $this->counter = 0;
    }

    /**
     * @ORM\PrePersist
     */
    public function insert()
    {
        $this->created = new \DateTime();
        $this->updated = new \DateTime();
        $this->type = 1;
    }

    /**
     * @ORM\PreUpdate
     */
    public function update()
    {
        $this->updated = new \DateTime();
    }

    public function addTranslations($translations)
    {
        if (is_array($translations) || $translations instanceof Collection){

            foreach($translations as $translation){
                $this->translations[] = $translation->setAdvertising($this);
            }

        } else {
            $this->translations[] = $translations->setAdvertising($this);
        }
    }

    public function removeTranslations($translations)
    {
        if (is_array($translations) || $translations instanceof Collection){
            foreach($translations as $translation){
                $this->translations->removeElement($translation);
            }
        }else {
            $this->translations->removeElement($translations);
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
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Asset
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param Asset $image
     *
     * @return $this
     */
    public function setImage($image)
    {
        $image->setUser($this->getUser());
        $this->image = $image;

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
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $link
     *
     * @return $this
     */
    public function setUrl($link)
    {
        $this->url = $link;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * @param ArrayCollection $translations
     *
     * @return $this
     */
    public function setTranslations($translations)
    {
        $this->translations = $translations;

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
     * @return AdvertisingPlace
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * @param AdvertisingPlace $place
     *
     * @return $this
     */
    public function setPlace($place)
    {
        $this->place = $place;

        return $this;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param int $count
     *
     * @return $this
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * @return int
     */
    public function getCounter()
    {
        return $this->counter;
    }

    /**
     * @param int $counter
     *
     * @return $this
     */
    public function setCounter($counter)
    {
        $this->counter = $counter;

        return $this;
    }


}