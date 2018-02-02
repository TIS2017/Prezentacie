<?php

namespace Robisk\ApplicationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * AnnouncementComments
 *
 * @ORM\Table(name="announcement_comments")
 * @ORM\Entity
 */
class AnnouncementComments
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="announcementComments")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var Announcement
     *
     * @ORM\ManyToOne(targetEntity="Announcement", inversedBy="comments")
     * @ORM\JoinColumn(name="announcement_id", referencedColumnName="id")
     */
    protected $announcement;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    protected $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    protected $date;

    /**
     * @var boolean
     *
     * @ORM\Column(name="valuated", type="integer")
     */
    protected $valuated;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return Announcement
     */
    public function getAnnouncement()
    {
        return $this->announcement;
    }

    /**
     * @param Announcement $announcement
     */
    public function setAnnouncement($announcement)
    {
        $this->announcement = $announcement;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return bool
     */
    public function isValuated()
    {
        return $this->valuated;
    }

    /**
     * @param bool $valuated
     */
    public function setValuated($valuated)
    {
        $this->valuated = $valuated;
    }


}