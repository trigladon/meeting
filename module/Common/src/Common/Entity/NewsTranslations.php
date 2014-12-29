<?php

namespace Common\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class News Translations
 *
 * @ORM\Table(name="news_translations")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class NewsTranslations extends BaseEntity
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
     * @var Language
     *
     * @ORM\ManyToOne(targetEntity="Language")
     * @ORM\JoinColumn(name="id_language", referencedColumnName="id")
     */
    protected $language;

    /**
     * @var News
     *
     * @ORM\ManyToOne(targetEntity="News", inversedBy="translations")
     * @ORM\JoinColumn(name="id_news", referencedColumnName="id")
     */
    protected $news;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string")
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(name="small_description", type="string")
     */
    protected $short;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string")
     */
    protected $description;

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
     * @return Language
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param Language $language
     * @return $this
     */
    public function setLanguage($language)
    {
        $this->language = $language;
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
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getShort()
    {
        return $this->short;
    }

    /**
     * @param string $short
     * @return $this
     */
    public function setShort($short)
    {
        $this->short = $short;
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
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }


}