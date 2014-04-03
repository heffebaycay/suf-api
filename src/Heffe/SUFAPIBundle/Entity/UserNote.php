<?php

namespace Heffe\SUFAPIBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;

/**
 * UserNote
 *
 * @ORM\Table(name="user_notes")
 * @ORM\Entity(repositoryClass="Heffe\SUFAPIBundle\Entity\UserNoteRepository")
 *
 * @ExclusionPolicy("all")
 */
class UserNote
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
     * @ORM\Column(name="content", type="text")
     *
     * @Expose
     * @Type("string")
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_created", type="datetime")
     *
     * @Expose
     * @Type("DateTime<'Y-m-d @ H:i'>")
     */
    private $dateCreated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_updated", type="datetime", nullable=true)
     *
     * @Expose
     * @Type("DateTime<'Y-m-d @ H:i'>")
     */
    private $dateUpdated;

    /**
     * @var \Heffe\SteamOpenIdBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Heffe\SteamOpenIdBundle\Entity\User", inversedBy="userNotesCreated")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Expose
     * @Type("Heffe\SteamOpenIdBundle\Entity\User")
     */
    private $author;

    /**
     * @var \Heffe\SUFAPIBundle\Entity\SteamUser
     *
     * @ORM\ManyToOne(targetEntity="Heffe\SUFAPIBundle\Entity\SteamUser", inversedBy="userNotes")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Expose
     * @Type("Heffe\SUFAPIBundle\Entity\SteamUser")
     */
    private $target;

    /**
     * @var boolean
     *
     * @ORM\Column(name="removed", type="boolean")
     */
    private $removed;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_removed", type="datetime", nullable=true)
     */
    private $dateRemoved;


    public function setId($id)
    {
        $this->id = $id;
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
     * Set content
     *
     * @param string $content
     * @return UserNote
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     * @return UserNote
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
     * @return UserNote
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
     * Set author
     *
     * @param \Heffe\SteamOpenIdBundle\Entity\User $author
     * @return UserNote
     */
    public function setAuthor(\Heffe\SteamOpenIdBundle\Entity\User $author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \Heffe\SteamOpenIdBundle\Entity\User 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set target
     *
     * @param \Heffe\SUFAPIBundle\Entity\SteamUser $target
     * @return UserNote
     */
    public function setTarget(\Heffe\SUFAPIBundle\Entity\SteamUser $target)
    {
        $this->target = $target;

        return $this;
    }

    /**
     * Get target
     *
     * @return \Heffe\SUFAPIBundle\Entity\SteamUser 
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * Set removed
     *
     * @param boolean $removed
     * @return UserNote
     */
    public function setRemoved($removed)
    {
        $this->removed = $removed;

        return $this;
    }

    /**
     * Get removed
     *
     * @return boolean 
     */
    public function getRemoved()
    {
        return $this->removed;
    }

    /**
     * Set dateRemoved
     *
     * @param \DateTime $dateRemoved
     * @return UserNote
     */
    public function setDateRemoved($dateRemoved)
    {
        $this->dateRemoved = $dateRemoved;

        return $this;
    }

    /**
     * Get dateRemoved
     *
     * @return \DateTime 
     */
    public function getDateRemoved()
    {
        return $this->dateRemoved;
    }
}
