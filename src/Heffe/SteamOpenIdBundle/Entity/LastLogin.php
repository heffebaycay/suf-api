<?php

namespace Heffe\SteamOpenIdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LastLogin
 *
 * @ORM\Table(name="last_logins")
 * @ORM\Entity
 */
class LastLogin
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
     * @ORM\Column(name="ipAddress", type="string", length=255)
     */
    private $ipAddress;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="loginDate", type="datetime")
     */
    private $loginDate;

    /**
     * @var string
     *
     * @ORM\Column(name="userAgent", type="string", length=255)
     */
    private $userAgent;

    /**
     * @var \Heffe\SteamOpenIdBundle\Entity\User $user
     *
     * @ORM\ManyToOne(targetEntity="Heffe\SteamOpenIdBundle\Entity\User", inversedBy="lastLogins")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

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
     * Set ipAddress
     *
     * @param string $ipAddress
     * @return LastLogin
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    /**
     * Get ipAddress
     *
     * @return string 
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * Set loginDate
     *
     * @param \DateTime $loginDate
     * @return LastLogin
     */
    public function setLoginDate($loginDate)
    {
        $this->loginDate = $loginDate;

        return $this;
    }

    /**
     * Get loginDate
     *
     * @return \DateTime 
     */
    public function getLoginDate()
    {
        return $this->loginDate;
    }

    /**
     * Set userAgent
     *
     * @param string $userAgent
     * @return LastLogin
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    /**
     * Get userAgent
     *
     * @return string 
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * Set user
     *
     * @param \Heffe\SteamOpenIdBundle\Entity\User $user
     * @return LastLogin
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
}
