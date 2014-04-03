<?php

namespace Heffe\SUFAPIBundle\Forms\Model;

use Heffe\SUFAPIBundle\Entity\SteamUser;
use Heffe\SUFAPIBundle\Entity\UserNote;

use Symfony\Component\Validator\Constraints as Assert;

class UserNoteFormModel
{
    private $id;

    /**
     * @Assert\NotBlank(message="User note content cannot be empty")
     */
    private $content;

    /**
     * @Assert\Regex(pattern="/^\d{17}$/", message="Invalid SteamId")
     */
    private $steamId64;


    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getSteamId64()
    {
        return $this->steamId64;
    }

    public function setSteamId64($steamId64)
    {
        $this->steamId64 = $steamId64;
    }

    public function toUserNote()
    {
        $userNote = new UserNote();
        $userNote->setContent($this->getContent());

        $target = new SteamUser();
        $target->setSteamId($this->getSteamId64());

        $userNote->setTarget($target);

        return $userNote;
    }
}