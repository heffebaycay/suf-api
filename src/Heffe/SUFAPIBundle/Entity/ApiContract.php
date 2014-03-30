<?php

namespace Heffe\SUFAPIBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ApiContract
 *
 * @ORM\Table(name="api_contracts")
 * @ORM\Entity(repositoryClass="Heffe\SUFAPIBundle\Entity\ApiContractRepository")
 */
class ApiContract
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
     * @ORM\Column(name="api_key", type="string", length=255)
     */
    private $apiKey;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_created", type="datetime")
     */
    private $dateCreated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_revoked", type="datetime", nullable=true)
     */
    private $dateRevoked;

    /**
     * @var boolean
     *
     * @ORM\Column(name="revoked", type="boolean")
     */
    private $revoked;

    /**
     * @var \Heffe\SteamOpenIdBundle\Entity\User $user
     *
     * @ORM\ManyToOne(targetEntity="Heffe\SteamOpenIdBundle\Entity\User", inversedBy="apiContracts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function __construct()
    {
        $this->setRevoked(false);
        $this->dateCreated = new \DateTime();
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
     * Set apiKey
     *
     * @param string $apiKey
     * @return ApiContract
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Get apiKey
     *
     * @return string 
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     * @return ApiContract
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
     * Set revoked
     *
     * @param boolean $revoked
     * @return ApiContract
     */
    public function setRevoked($revoked)
    {
        $this->revoked = $revoked;

        return $this;
    }

    /**
     * Get revoked
     *
     * @return boolean 
     */
    public function getRevoked()
    {
        return $this->revoked;
    }

    /**
     * Set user
     *
     * @param \Heffe\SteamOpenIdBundle\Entity\User $user
     * @return ApiContract
     */
    public function setUser(\Heffe\SteamOpenIdBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Heffe\SteamOpenIdBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set dateRevoked
     *
     * @param \DateTime $dateRevoked
     * @return ApiContract
     */
    public function setDateRevoked($dateRevoked)
    {
        $this->dateRevoked = $dateRevoked;

        return $this;
    }

    /**
     * Get dateRevoked
     *
     * @return \DateTime 
     */
    public function getDateRevoked()
    {
        return $this->dateRevoked;
    }
}
