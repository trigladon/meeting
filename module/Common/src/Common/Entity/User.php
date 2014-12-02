<?php

namespace Common\Entity;

use ZfcRbac\Identity\IdentityInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Zend\Crypt\Password\Bcrypt;
use Zend\Math\Rand;
use Rbac\Role\RoleInterface;

/**
 * Class User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class User extends BaseEntity implements IdentityInterface
{

    const STATUS_NO_ACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_BANNED = 2;

    const DELETED_YES = 'yes';
    const DELETED_NO = 'no';

    const TYPE_COMPANY = 'company';
    const TYPE_USER = 'user';

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
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=50, nullable=false)
     */
    protected $password;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=50, nullable=false)
     */
    protected $salt;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    protected $status;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", nullable=false)
     */
    protected $type;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255, nullable=true)
     */
    protected $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255, nullable=true)
     */
    protected $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="middle_name", type="string", length=255, nullable=true)
     */
    protected $middleName;

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
     * @var string
     *
     * @ORM\Column(name="birthday", type="text", nullable=false)
     */
    protected $birthday;

    /**
     * @var string
     *
     * @ORM\Column(name="deleted", type="string", length=3, nullable=false)
     */
    protected $deleted;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=true)
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated", type="datetime", nullable=true)
     */
    protected $updated;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="Role")
     */
    protected $roles;

    /**
     * @var UserCode
     *
     * @ORM\OneToOne(targetEntity="UserCode", mappedBy="user", cascade={"persist","remove"})
     */
    protected $code;

    /**
     * @var UserBanned
     *
     * @ORM\OneToMany(targetEntity="UserBanned", mappedBy="admin")
     * @ORM\JoinColumn(name="id_banned", referencedColumnName="id")
     */
    protected $adminBan;

    /**
     * @var UserBanned
     *
     * @ORM\OneToOne(targetEntity="UserBanned", mappedBy="userBanned")
     */
    protected $banned;

    public function __construct()
    {
        $this->roles    = new ArrayCollection();
        $this->adminBan = new ArrayCollection();
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
        //$this->setSalt(Rand::getBytes(Bcrypt::MIN_SALT_SIZE));
        $this->deleted = self::DELETED_NO;
    }

    /**
     * @return Collection
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param Collection $roles
     *
     * @return $this
     */
    public function setRoles(Collection $roles)
    {
        $this->roles->clear();
        foreach ($roles as $role) {
            if ($role instanceof RoleInterface) {
                $this->roles[] = $role;
            }
        }

        return $this;
    }

    /**
     * @param RoleInterface $role
     *
     * @return $this
     */
    public function addRoles($role)
    {
        if (is_array($role) || $role instanceof ArrayCollection) {
            foreach($role as $newRole) {
                $this->addRoles($newRole);
            }
        }else {
            $this->roles[] = $role;
        }

        return $this;
    }

    /**
     * @param RoleInterface $role
     *
     * @return $this
     */
    public function removeRoles($role)
    {
        if (is_array($role) || $role instanceof ArrayCollection) {
            foreach($role as $newRole) {
                $this->removeRoles($newRole);
            }
        }else {
            $this->roles->removeElement($role);
        }

        return $this;
    }

    /**
     * @return UserBanned
     */
    public function getAdminBan()
    {
        return $this->adminBan;
    }

    /**
     * @param UserBanned $adminBan
     *
     * @return $this
     */
    public function setAdminBan($adminBan)
    {
        $this->adminBan = $adminBan;

        return $this;
    }

    /**
     * @return UserBanned
     */
    public function getBanned()
    {
        return $this->banned;
    }

    /**
     * @param UserBanned $banned
     *
     * @return $this
     */
    public function setBanned($banned)
    {
        $this->banned = $banned;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     *
     * @return $this
     */
    public function setCode(UserCode $code)
    {
        $this->code = $code->setUser($this);

        return $this;
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
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param string $email
     *
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
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
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $password
     *
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $salt
     *
     * @return $this
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
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
    public function getUpdated()
    {
        return $this->updated;
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
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @param \DateTime $birthday
     *
     * @return $this
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * @return string
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * @param string $deleted
     *
     * @return $this
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     *
     * @return $this
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     *
     * @return $this
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string
     */
    public function getMiddleName()
    {
        return $this->middleName;
    }

    /**
     * @param string $middleName
     *
     * @return $this
     */
    public function setMiddleName($middleName)
    {
        $this->middleName = $middleName;

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
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    public function getFullName()
    {
        return $this->firstName . ' ' . $this->getLastName();
    }


}