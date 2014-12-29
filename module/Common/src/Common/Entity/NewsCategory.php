<?php

namespace Common\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class News Category
 *
 * @ORM\Table(name="news_category")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class NewsCategory extends BaseEntity
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
     * @var NewsCategoryTranslations
     *
     * @ORM\OneToMany(targetEntity="NewsCategoryTranslations", mappedBy="category", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="id", referencedColumnName="id_category")
     */
    protected $translations;

    /**
     * @var News
     *
     * @ORM\OneToMany(targetEntity="News", mappedBy="category", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="id", referencedColumnName="id_category")
     */
    protected $news;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Asset", cascade={"persist", "remove"})
     * @ORM\JoinTable(name="asset_news_category",
     *      joinColumns={@ORM\JoinColumn(name="id_category", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="id_asset", referencedColumnName="id")}
     *      )
     **/
    protected $assets;

    public function __construct()
    {
        $this->assets = new ArrayCollection();
        $this->translations = new ArrayCollection();
        $this->news = new ArrayCollection();
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

    public function addTranslations($translations)
    {
        if (is_array($translations) || $translations instanceof Collection){

            foreach($translations as $translation){
                $this->translations[] = $translation->setCategory($this);
            }

        } else {
            $this->translations[] = $translations->setCategory($this);
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

    public function addNews($news)
    {
        if (is_array($news) || $news instanceof Collection){

            foreach($news as $new){
                $this->news[] = $new->setCategory($this);
            }

        } else {
            $this->news[] = $news->setCategory($this);
        }
    }

    public function removeNews($news)
    {
        if (is_array($news) || $news instanceof Collection){
            foreach($news as $new){
                $this->news->removeElement($new);
            }
        }else {
            $this->news->removeElement($news);
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
     * @return NewsCategoryTranslations
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * @param NewsCategoryTranslations $translations
     * @return $this
     */
    public function setTranslations($translations)
    {
        $this->translations = $translations;
        return $this;
    }

    /**
     * @return News
     */
    public function getNews()
    {
        return $this->news;
    }

    /**
     * @param News $news
     * @return $this
     */
    public function setNews($news)
    {
        $this->news = $news;
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