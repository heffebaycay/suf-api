<?php

namespace Heffe\SUFAPIBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\SerializedName;

/**
 * SteamUser
 *
 * @ORM\Table(name="steam_user")
 * @ORM\Entity(repositoryClass="Heffe\SUFAPIBundle\Entity\SteamUserRepository")
 *
 * @ExclusionPolicy("all")
 */
class SteamUser
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
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreated", type="datetime")
     */
    private $dateCreated;

    /**
     * @ORM\OneToMany(targetEntity="Heffe\SUFAPIBundle\Entity\UserNote", mappedBy="target")
     */
    private $userNotes;

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
     * @return SteamUser
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
     * @return SteamUser
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
     * Constructor
     */
    public function __construct()
    {
        $this->userNotes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add userNotes
     *
     * @param \Heffe\SUFAPIBundle\Entity\UserNote $userNotes
     * @return SteamUser
     */
    public function addUserNote(\Heffe\SUFAPIBundle\Entity\UserNote $userNotes)
    {
        $userNotes->setTarget($this);
        $this->userNotes[] = $userNotes;

        return $this;
    }

    /**
     * Remove userNotes
     *
     * @param \Heffe\SUFAPIBundle\Entity\UserNote $userNotes
     */
    public function removeUserNote(\Heffe\SUFAPIBundle\Entity\UserNote $userNotes)
    {
        $this->userNotes->removeElement($userNotes);
    }

    /**
     * Get userNotes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUserNotes()
    {
        return $this->userNotes;
    }
}
