<?php

namespace Robisk\ApplicationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Announcement
 *
 * @ORM\Table(name="announcement_frontend")
 * @ORM\Entity
 */
class AnnouncementFrontend
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
     * @var datetime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;

    /**
     * @var datetime
     *
     * @ORM\Column(name="updated", type="datetime")
     */
    protected $updated;

    /**
     * @var integer
     *
     * @ORM\Column(name="updated_count", type="integer")
     */
    protected $updatesCount;

    public function __construct()
    {
        $time = new \DateTime('now');
        $this->created = $time;
        $this->updated = $time;
        $this->updatesCount = 0;
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
     * Set updated count
     *
     * @param integer $updatedCount
     * @return Announcement
     */
    public function setUpdatedCount($updatedCount)
    {
        $this->updatedCount = $updatedCount;
        return $this;
    }

    /**
     * Get updated count
     *
     * @return integer
     */
    public function getUpdatedCount()
    {
        return $this->updatedCount;
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
     * Set updatesCount
     *
     * @param integer $updatesCount
     * @return AnnouncementFrontend
     */
    public function setUpdatesCount($updatesCount)
    {
        $this->updatesCount = $updatesCount;

        return $this;
    }

    /**
     * Get updatesCount
     *
     * @return integer
     */
    public function getUpdatesCount()
    {
        return $this->updatesCount;
    }
}
