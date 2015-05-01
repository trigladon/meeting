<?php

namespace Common\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Auction
 *
 * @ORM\Table(name="auction")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Auction extends BaseEntity
{

    const STATUS_ACTIVE = 1;
    const STATUS_FINISH = 2;
    const STATUS_CLOSE = 3;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Asset
     *
     * @ORM\OneToOne(targetEntity="Asset", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="asset_image", referencedColumnName="id")
     */
    protected $image;

    /**
     * @var Asset
     *
     * @ORM\OneToOne(targetEntity="Asset", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="asset_video", referencedColumnName="id")
     */
    protected $video;

    /**
     * @var AuctionRate
     *
     * @ORM\OneToMany(targetEntity="AuctionRate", mappedBy="auction", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="id", referencedColumnName="id_auction")
     */
    protected $rates;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var Patient
     *
     * @ORM\ManyToOne(targetEntity="Patient")
     * @ORM\JoinColumn(name="id_patient", referencedColumnName="id")
     */
    protected $patient;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="datetime", nullable=true)
     */
    protected $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_date", type="datetime", nullable=true)
     */
    protected $endDate;

    /**
     * @var integer
     */
    protected $lengthDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    protected $status;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", nullable=true)
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(name="about", type="string", nullable=true)
     */
    protected $about;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Asset", cascade={"persist", "remove"})
     * @ORM\JoinTable(name="asset_auction",
     *      joinColumns={@ORM\JoinColumn(name="id_auction", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="id_asset", referencedColumnName="id")}
     *      )
     **/
    protected $assets;

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
        $this->assets = new ArrayCollection();
        $this->rates = new ArrayCollection();
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
     * @param $assets
     *
     * @return $this
     */
    public function addAssets($assets)
    {
        if (is_array($assets) || $assets instanceof Collection){
            foreach($assets as $asset){
                if (!($asset->getUser() instanceof User)) {
                    $asset->setUser($this->getUser());
                }
                $this->assets[] = $asset;
            }
        } else {
            if (!($assets->getUser() instanceof User)) {
                $assets->setUser($this->getUser());
            }
            $this->assets[] = $assets;
        }

        return $this;
    }

    public function removeAssets($assets)
    {
        if (is_array($assets) || $assets instanceof Collection){
            foreach($assets as $asset){
                $this->assets->removeElement($asset);
            }
        }else {
            $this->assets->removeElement($assets);
        }

        return $this;
    }

    /**
     * @param $rates
     *
     * @return $this
     */
    public function addRates($rates)
    {
        if (is_array($rates) || $rates instanceof Collection){
            foreach($rates as $rate){
                $this->rates[] = $rate;
            }
        } else {
            $this->rates[] = $rates;
        }

        return $this;
    }

    public function removeRates($rates)
    {
        if (is_array($rates) || $rates instanceof Collection){
            foreach($rates as $rate){
                $this->rates->removeElement($rate);
            }
        }else {
            $this->rates->removeElement($rates);
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
        if (!($this->image->getUser() instanceof User)) {
            $this->image->setUser($this->getUser());
        }

        return $this;
    }

    /**
     * @return Asset
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * @param Asset $video
     *
     * @return $this
     */
    public function setVideo($video)
    {
        $this->video = $video;
        if (!($this->video->getUser() instanceof User)) {
            $this->video->setUser($this->getUser());
        }

        return $this;
    }

    /**
     * @return AuctionRate
     */
    public function getRates()
    {
        return $this->rates;
    }

    /**
     * @param AuctionRate $rates
     *
     * @return $this
     */
    public function setRates($rates)
    {
        $this->rates = $rates;

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
     * @return Patient
     */
    public function getPatient()
    {
        return $this->patient;
    }

    /**
     * @param Patient $patient
     *
     * @return $this
     */
    public function setPatient($patient)
    {
        $this->patient = $patient;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param \DateTime $startDate
     *
     * @return $this
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param \DateTime $endDate
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
    public function getLengthDate()
    {
        return $this->lengthDate;
    }

    /**
     * @param int $lengthDate
     *
     * @return $this
     */
    public function setLengthDate($lengthDate)
    {
        $this->lengthDate = $lengthDate;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string
     */
    public function getAbout()
    {
        return $this->about;
    }

    /**
     * @param string $about
     *
     * @return $this
     */
    public function setAbout($about)
    {
        $this->about = $about;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getAssets()
    {
        return $this->assets;
    }

    /**
     * @param ArrayCollection $assets
     *
     * @return $this
     */
    public function setAssets($assets)
    {
        $this->assets = $assets;

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

    public function assetsGetMethods()
    {
        return [
            'getImage',
            'getVideo',
            'getAssets',
        ];
    }




}