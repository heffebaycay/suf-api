<?php

namespace Heffe\SUFAPIBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VanityURLCacheEntry
 *
 * @ORM\Table(name="vanity_url_cache")
 * @ORM\Entity(repositoryClass="Heffe\SUFAPIBundle\Entity\VanityURLCacheRepository")
 */
class VanityURLCacheEntry
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
     * @ORM\Column(name="vanity_url", type="string", length=255, unique=true)
     */
    private $vanityURL;

    /**
     * @var string
     *
     * @ORM\Column(name="steam_id", type="string", length=20)
     */
    private $steamId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_created", type="datetime")
     */
    private $dateCreated;


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
     * Set vanityURL
     *
     * @param string $vanityURL
     * @return VanityURLCacheEntry
     */
    public function setVanityURL($vanityURL)
    {
        $this->vanityURL = $vanityURL;

        return $this;
    }

    /**
     * Get vanityURL
     *
     * @return string 
     */
    public function getVanityURL()
    {
        return $this->vanityURL;
    }

    /**
     * Set steamId
     *
     * @param string $steamId
     * @return VanityURLCacheEntry
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
     * @return VanityURLCacheEntry
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
}
