<?php

namespace Robisk\ApplicationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Announcement
 *
 * @ORM\Table(name="announcement")
 * @ORM\Entity
 */
class Announcement
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
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     * @Assert\NotBlank()
     */
    protected $content;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\NotBlank()
     */
    protected $title;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated", type="datetime")
     */
    protected $updated;

    /**
     * @var Subject
     *
     * @ORM\ManyToOne(targetEntity="Subject", inversedBy="announcements")
     * @ORM\JoinColumn(name="subject_id", referencedColumnName="id")
     */
    protected $subject;

    /**
     * @var integer
     *
     * @ORM\Column(name="updated_count", type="integer")
     */
    protected $updatesCount;

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AnnouncementComment", mappedBy="announcement", cascade={"persist", "remove"})
     */
    protected $comments;

    public function __construct()
    {
        $time = new \DateTime('now');
        $this->created = $time;
        $this->updated = $time;
        $this->updatesCount = 0;
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Announcement
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
     * Set title
     *
     * @param string $title
     * @return Announcement
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set created time
     *
     * @param datetime $created
     * @return Announcement
     */
    public function setCreated($created)
    {
        $this->created = $created;
        return $this;
    }

    /**
     * Get created time
     *
     * @return datetime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated time
     *
     * @param datetime $updated
     * @return Announcement
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
        return $this;
    }

    /**
     * Get updated time
     *
     * @return datetime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set updates count
     *
     * @param integer $updatesCount
     * @return Announcement
     */
    public function setUpdatesCount($updatesCount)
    {
        $this->updatesCount = $updatesCount;
        return $this;
    }

    /**
     * Get updates count
     *
     * @return integer
     */
    public function getUpdatesCount()
    {
        return $this->updatesCount;
    }

    /**
     * Increase update count by const
     *
     * @param integer $inc
     * @return Announcement
     */
    public function increaseUpdatedCount($inc = null)
    {
        if ($inc == null) {
            $inc = 1;
        }

        $this->updatesCount += $inc;
        return $this;
    }

    /**
     * Set subject
     *
     * @param Subject $subject
     * @return Announcement
     */
    public function setSubject(Subject $subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * Get subject
     *
     * @return Subject
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="video_URL", type="string", length=255, nullable=true, options={"default": NULL})
     * @Assert\NotBlank()
     */
    protected $videoURL;

    public function getVideoURL()
    {
        return $this->videoURL;
    }

    public function setVideoURL($videoURL)
    {
        $result = $videoURL;
        if (strpos("$result", "youtube.com")!==false) {
            $result = str_replace("watch?v=", "embed/", $result);
        }
        elseif (strpos($result, "ted.com")!==false) {
            $result = str_replace("www.ted.com", "embed.ted.com", $result);
        }

        $this->videoURL = $result;
    }

    public function getComments(){
        return $this->comments;
    }

    public function addComment(AnnouncementComment $comment){
        $this->comments->add($comment);
    }
}
