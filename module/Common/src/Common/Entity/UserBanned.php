<?php

namespace Common\Entity;

use \Doctrine\ORM\Mapping as ORM;

/**
 * Class UserBanned
 *
 * @ORM\Table(name="user_banned")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class UserBanned extends BaseEntity
{

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
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
     * @ORM\Column(name="reason", type="string")
     */
    protected $reason;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;

//    /**
//     * @var User
//     *
//     * @ORM\ManyToOne(targetEntity="User", inversedBy="adminBan")
//     * @ORM\JoinColumn(name="id_admin", referencedColumnName="id")
//     */
//    protected $admin;
//
//    /**
//     * @var User
//     *
//     * @ORM\OneToOne(targetEntity="User", inversedBy="banned")
//     * @ORM\JoinColumn(name="id_banned", referencedColumnName="id")
//     */
//    protected $userBanned;

    /**
     * @ORM\PrePersist
     */
    public function preInsert()
    {
        $this->created = new \DateTime();
    }

    /**
     * @return User
     */
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * @param User $admin
     *
     * @return $this
     */
    public function setAdmin($admin)
    {
        $this->admin = $admin;

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
     * @return User
     */
    public function getUserBanned()
    {
        return $this->userBanned;
    }

    /**
     * @param User $userBanned
     *
     * @return $this
     */
    public function setUserBanned($userBanned)
    {
        $this->userBanned = $userBanned;

        return $this;
    }

    /**
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * @param string $reason
     */
    public function setReason($reason)
    {
        $this->reason = $reason;
    }

}