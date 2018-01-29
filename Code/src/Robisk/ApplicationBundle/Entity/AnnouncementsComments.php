<?php

namespace Robisk\ApplicationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * AnnouncementsComments
 *
 * @ORM\Table(name="annoucements_comments")
 * @ORM\Entity
 */
class AnnouncementsComments
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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="announcementsComments")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var Announcement
     *
     * @ORM\ManyToOne(targetEntity="Announcement", inversedBy="announcementsComments")
     * @ORM\JoinColumn(name="annoucement_id", referencedColumnName="id")
     */
    protected $announcement;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    protected $content;

    /**
     * @var integer
     *
     * @ORM\OneToOne(targetEntity="AnnouncementsComments")
     * @ORM\JoinColumn(name="response_to_id", referencedColumnName="id")
     */

    protected $response;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    protected $date;

    /**
     * @var boolean
     *
     * @ORM\Column(name="valueted", type="integer")
     */
    protected $valueted;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * @return int
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param int $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
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
    public function isValueted()
    {
        return $this->valueted;
    }

    /**
     * @param bool $valueted
     */
    public function setValueted($valueted)
    {
        $this->valueted = $valueted;
    }


}