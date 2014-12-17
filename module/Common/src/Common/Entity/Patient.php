<?php

namespace Common\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Patient
 *
 * @ORM\Table(name="patient")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Patient extends BaseEntity
{

    const CHECK_NO = 'no';
    const CHECK_YES = 'yes';

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
     * @ORM\Column(name="title", type="string", length=511, nullable=false)
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", nullable=false)
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(name="end_date", type="string")
     */
    protected $endDate;

    /**
     * @var float
     *
     * @ORM\Column(name="summa", type="float")
     */
    protected $summa;

    /**
     * @var string
     *
     * @ORM\Column(name="checked", type="string", length=3, nullable=false)
     */
    protected $check;

    /**
     * @var string
     *
     * @ORM\Column(name="published", type="string", length=3, nullable=false)
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
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="patient")
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var Asset
     *
     * @ORM\OneToOne(targetEntity="Asset", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="asset_image", referencedColumnName="id")
     */
    protected $image;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Asset", cascade={"persist", "remove"})
     * @ORM\JoinTable(name="asset_patient",
     *      joinColumns={@ORM\JoinColumn(name="id_patient", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="id_asset", referencedColumnName="id")}
     *      )
     **/
    protected $assets;

    public function __construct()
    {
        $this->assets = new ArrayCollection();
    }

    /**
     * @ORM\PreUpdate
     */
    public function update()
    {
        $this->setUpdated(new \DateTime());
    }

    /**
     * @ORM\PrePersist
     */
    public function insert()
    {
        $this->setCreated(new \DateTime());
        $this->setUpdated(new \DateTime());
    }

    /**
     * @return ArrayCollection
     */
    public function getAssets()
    {
        return $this->assets;
    }

    public function setAssets($assets)
    {
        $this->assets = $assets;
    }

    /**
     * @param ArrayCollection $assets
     *
     * @return $this
     */
    public function addAssets(ArrayCollection $assets)
    {
        //$this->assets->clear();

        foreach($assets as $asset){
            $this->assets[] = $asset;
        }


        return $this;
    }

    public function removeAssets($assets)
    {
        if (is_array($assets) || $assets instanceof Collection){
            foreach($assets as $asset){
                $this->removeAssets($asset);
            }
        }else {
            $this->assets->removeElement($assets);
        }

        return $this;
    }


    /**
     * @return mixed
     */
    public function getCheck()
    {
        return $this->check;
    }

    /**
     * @param mixed $check
     *
     * @return $this
     */
    public function setCheck($check)
    {
        $this->check = $check;

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
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param string $endDate
     *
     * @return $this
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

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
        $this->image = $image;

        return $this;
    }

    /**
     * @return float
     */
    public function getSumma()
    {
        return $this->summa;
    }

    /**
     * @param float $summa
     *
     * @return $this
     */
    public function setSumma($summa)
    {
        $this->summa = $summa;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

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






}