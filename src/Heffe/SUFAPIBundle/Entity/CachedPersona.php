<?php

namespace Heffe\SUFAPIBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CachedPersona
 *
 * @ORM\Table(name="cached_persona")
 * @ORM\Entity
 */
class CachedPersona
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="persona_name", type="string", length=255)
     */
    private $personaName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_created", type="datetime")
     */
    private $dateCreated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_updated", type="datetime", nullable=true)
     */
    private $dateUpdated;


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
     * Set personaName
     *
     * @param string $personaName
     * @return CachedPersona
     */
    public function setPersonaName($personaName)
    {
        $this->personaName = $personaName;

        return $this;
    }

    /**
     * Get personaName
     *
     * @return string 
     */
    public function getPersonaName()
    {
        return $this->personaName;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     * @return CachedPersona
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
     * @return CachedPersona
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
}
