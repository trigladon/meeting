<?php

namespace Common\Entity;

use Rbac\Role\HierarchicalRoleInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use ZfcRbac\Permission\PermissionInterface;

/**
 * Class Role
 *
 * @ORM\Table(name="roles")
 * @ORM\Entity
 */
class Role extends BaseEntity implements HierarchicalRoleInterface
{
	const ROLE_GUEST = 'Guest';
	const ROLE_USER = 'User';
	const ROLE_MANAGER = 'Manager';
	const ROLE_ADMIN = 'Admin';
	const ROLE_GOD_MODE = 'God mode';

    /**
     * @var int|null
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=48, unique=true)
     */
    protected $name;

    /**
     * @var HierarchicalRoleInterface[]|\Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Role")
     */
    protected $children = [];

    /**
     * @var PermissionInterface[]|\Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Permission", indexBy="name", fetch="EAGER")
     */
    protected $permissions;

    /**
     * Init the Doctrine collection
     */
    public function __construct()
    {
        $this->children    = new ArrayCollection();
        $this->permissions = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set the role name
     *
     * @param  string $name
     * @return void
     */
    public function setName($name)
    {
        $this->name = (string) $name;
    }

    /**
     * Get the role name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function addChild(HierarchicalRoleInterface $child)
    {
        $this->children[] = $child;
    }

    /**
     * {@inheritDoc}
     */
    public function addPermission($permission)
    {
        if (is_string($permission)) {
            $permission = new Permission($permission);
        }

        $this->permissions[(string) $permission] = $permission;
    }

    /**
     * {@inheritDoc}
     */
    public function hasPermission($permission)
    {
        // This can be a performance problem if your role has a lot of permissions. Please refer
        // to the cookbook to an elegant way to solve this issue

        return isset($this->permissions[(string) $permission]);
    }

    /**
     * {@inheritDoc}
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * {@inheritDoc}
     */
    public function hasChildren()
    {
        return !$this->children->isEmpty();
    }
}