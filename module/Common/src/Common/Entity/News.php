<?php

namespace Common\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class News
 *
 * @ORM\Table(name="news")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class News extends BaseEntity
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
     * @var NewsCategory
     *
     * @ORM\ManyToOne(targetEntity="NewsCategory", inversedBy="news")
     * @ORM\JoinColumn(name="id_category", referencedColumnName="id")
     */
    protected $category;

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
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="NewsTranslations", mappedBy="news", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="id", referencedColumnName="id_news")
     */
    protected $translations;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Asset", cascade={"persist", "remove"})
     * @ORM\JoinTable(name="asset_news",
     *      joinColumns={@ORM\JoinColumn(name="id_news", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="id_asset", referencedColumnName="id")}
     *      )
     **/
    protected $assets;

    public function __construct()
    {
        $this->assets = new ArrayCollection();
        $this->translations = new ArrayCollection();
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

    public function addAssets($assets)
    {
        if (is_array($assets) || $assets instanceof Collection){
            foreach($assets as $asset){
                if (!($asset->getUser() instanceof User)){
                    $asset->setUser($this->getUser());
                }
                $this->assets[] = $asset;
            }
        } else {
            if (!($assets->getUser() instanceof User)){
                $assets->setUser($this->getUser());
            }
            $this->assets[] = $assets;
        }
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

    public function addTranslations($translations)
    {
        if (is_array($translations) || $translations instanceof Collection){

            foreach($translations as $translation){
                $this->translations[] = $translation->setNews($this);
            }

        } else {
            $this->translations[] = $translations->setNews($this);
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
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return NewsCategory
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param NewsCategory $category
     * @return $this
     */
    public function setCategory($category)
    {
        $this->category = $category;
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
     * @return $this
     */
    public function setCreated($created)
    {
        $this->created = $created;
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
     * @return $this
     */
    public function setTranslations($translations)
    {
        $this->translations = $translations;
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
     * @return $this
     */
    public function setAssets($assets)
    {
        $this->assets = $assets;
        return $this;
    }



}