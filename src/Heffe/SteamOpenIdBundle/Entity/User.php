<?php

namespace Heffe\SteamOpenIdBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\UserInterface;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\SerializedName;

/**
 * User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity
 *
 * @ExclusionPolicy("all")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Expose
     * @Type("integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="steamId", type="string", length=50)
     *
     * @Expose
     * @Type("string")
     * @SerializedName("steamId")
     */
    private $steamId;

    /**
     * @var string
     *
     * @ORM\Column(name="persona", type="string", length=100)
     *
     * @Expose
     * @Type("string")
     */
    private $persona;

    /**
     * @ORM\ManyToMany(targetEntity="Heffe\SteamOpenIdBundle\Entity\Group", inversedBy="users")
     */
    private $groups;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreated", type="datetime")
     */
    private $dateCreated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateUpdated", type="datetime", nullable=true)
     */
    private $dateUpdated;

    /**
     * @ORM\OneToMany(targetEntity="Heffe\SteamOpenIdBundle\Entity\LastLogin", mappedBy="user")
     */
    private $lastLogins;

    /**
     * @ORM\OneToMany(targetEntity="Heffe\SUFAPIBundle\Entity\ApiContract", mappedBy="user")
     */
    private $apiContracts;

    /**
     * @ORM\OneToMany(targetEntity="Heffe\SUFAPIBundle\Entity\UserNote", mappedBy="author")
     */
    private $userNotesCreated;

    public function __construct()
    {
        $this->dateCreated = new \DateTime();
        $this->lastLogins = new ArrayCollection();
        $this->apiContracts = new ArrayCollection();
        $this->userNotesCreated = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set steamId
     *
     * @param string $steamId
     * @return User
     */
    public function setSteamId($steamId)
    {
        $this->steamId = $steamId;

        return $this;
    }

    /**
     * Get steamId
     *
     * @return string 
     */
    public function getSteamId()
    {
        return $this->steamId;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     * @return User
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Get dateCreated
     *
     * @return \DateTime 
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Set dateUpdated
     *
     * @param \DateTime $dateUpdated
     * @return User
     */
    public function setDateUpdated($dateUpdated)
    {
        $this->dateUpdated = $dateUpdated;

        return $this;
    }

    /**
     * Get dateUpdated
     *
     * @return \DateTime 
     */
    public function getDateUpdated()
    {
        return $this->dateUpdated;
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     */
    public function serialize()
    {
        return serialize(array($this->id, $this->steamId, $this->persona));
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->steamId,
            $this->persona
            ) = unserialize($serialized);
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return Role[] The user roles
     */
    public function getRoles()
    {
        return $this->groups->toArray();
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword()
    {
        return 'heffearchive';
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return '';
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->steamId;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {

    }

    public function equals(UserInterface $user)
    {
        if(!user instanceof User)
        {
            return false;
        }

        return $this->getUsername() == $user->getUsername();
    }

    /**
     * Add groups
     *
     * @param \Heffe\SteamOpenIdBundle\Entity\Group $groups
     * @return User
     */
    public function addGroup(\Heffe\SteamOpenIdBundle\Entity\Group $groups)
    {
        $this->groups[] = $groups;

        return $this;
    }

    /**
     * Remove groups
     *
     * @param \Heffe\SteamOpenIdBundle\Entity\Group $groups
     */
    public function removeGroup(\Heffe\SteamOpenIdBundle\Entity\Group $groups)
    {
        $this->groups->removeElement($groups);
    }

    /**
     * Get groups
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Add lastLogins
     *
     * @param \Heffe\SteamOpenIdBundle\Entity\LastLogin $lastLogins
     * @return User
     */
    public function addLastLogin(\Heffe\SteamOpenIdBundle\Entity\LastLogin $lastLogins)
    {
        $lastLogins->setUser($this);
        $this->lastLogins[] = $lastLogins;

        return $this;
    }

    /**
     * Remove lastLogins
     *
     * @param \Heffe\SteamOpenIdBundle\Entity\LastLogin $lastLogins
     */
    public function removeLastLogin(\Heffe\SteamOpenIdBundle\Entity\LastLogin $lastLogins)
    {
        $this->lastLogins->removeElement($lastLogins);
    }

    /**
     * Get lastLogins
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLastLogins()
    {
        return $this->lastLogins;
    }

    /**
     * Set persona
     *
     * @param string $persona
     * @return User
     */
    public function setPersona($persona)
    {
        $this->persona = $persona;

        return $this;
    }

    /**
     * Get persona
     *
     * @return string 
     */
    public function getPersona()
    {
        return $this->persona;
    }

    /**
     * Add apiContracts
     *
     * @param \Heffe\SUFAPIBundle\Entity\ApiContract $apiContracts
     * @return User
     */
    public function addApiContract(\Heffe\SUFAPIBundle\Entity\ApiContract $apiContracts)
    {
        $apiContracts->setUser($this);
        $this->apiContracts[] = $apiContracts;

        return $this;
    }

    /**
     * Remove apiContracts
     *
     * @param \Heffe\SUFAPIBundle\Entity\ApiContract $apiContracts
     */
    public function removeApiContract(\Heffe\SUFAPIBundle\Entity\ApiContract $apiContracts)
    {
        $this->apiContracts->removeElement($apiContracts);
    }

    /**
     * Get apiContracts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getApiContracts()
    {
        return $this->apiContracts;
    }

    /**
     * Add userNotesCreated
     *
     * @param \Heffe\SUFAPIBundle\Entity\UserNote $userNotesCreated
     * @return User
     */
    public function addUserNotesCreated(\Heffe\SUFAPIBundle\Entity\UserNote $userNotesCreated)
    {
        $userNotesCreated->setAuthor($this);
        $this->userNotesCreated[] = $userNotesCreated;

        return $this;
    }

    /**
     * Remove userNotesCreated
     *
     * @param \Heffe\SUFAPIBundle\Entity\UserNote $userNotesCreated
     */
    public function removeUserNotesCreated(\Heffe\SUFAPIBundle\Entity\UserNote $userNotesCreated)
    {
        $this->userNotesCreated->removeElement($userNotesCreated);
    }

    /**
     * Get userNotesCreated
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUserNotesCreated()
    {
        return $this->userNotesCreated;
    }
}
