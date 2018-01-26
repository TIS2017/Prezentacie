<?php

namespace Robisk\ApplicationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Presentation
 *
 * @ORM\Table(name="presentation")
 * @ORM\Entity
 */
class Presentation
{
    const STATUS_NOT_PRESENTED = 0;
    const STATUS_PRESENTED = 1;
    const STATUS_VALUATED = 2;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="UserSubjectLookup", mappedBy="presentation")
     */
    protected $userSubjectLookup;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255)
     */
    protected $path;

    /**
     * @var UploadedFile
     *
     * @Assert\File(maxSize="6000000")
     */
    protected $file;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer")
     */
    protected $status;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="presentationsCreated")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
     */
    protected $owner;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="UserPresentationComments", mappedBy="presentation")
     */

    protected $userPresentationComments;

    public function __construct()
    {
        $this->userSubjectLookup = new \Doctrine\Common\Collections\ArrayCollection();
        $this->status = self::STATUS_NOT_PRESENTED;
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
     * Add user subject lookup
     *
     * @param UserSubjectLookup $userSubjectLookup
     * @return Presentation
     */
    public function addUserSubjectLookup(UserSubjectLookup $userSubjectLookup)
    {
        $this->userSubjectLookup->add($userSubjectLookup);
        return $this;
    }

    /**
     * Remove user subject lookup
     *
     * @param UserSubjectLookup $userSubjectLookup
     * @return Presentation
     */
    public function removeUserSubjectLookup(UserSubjectLookup $userSubjectLookup)
    {
        $this->userSubjectLookup->removeElement($userSubjectLookup);
        return $this;
    }

    /**
     * Get user subject lookup
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getUserSubjectLookup()
    {
        return $this->userSubjectLookup;
    }

    /**
     * Get subject
     *
     * @return Subject
     */
    public function getSubject()
    {
        foreach ($this->userSubjectLookup as $usl) {
            $subject = $usl->getSubject();
        }

        return $subject;
    }

    /**
     * Get users
     *
     * @return array
     */
    public function getUsers()
    {
        $users = array();
        foreach ($this->userSubjectLookup as $usl) {
            $users[] = $usl->getUser();
        }

        return $users;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Presentation
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
     * Set path
     *
     * @param string $path
     * @return Presentation
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->id .'_'. $this->path;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getDownloadPath()
    {
        return '/../../../../web/'. $this->getUploadDir() .'/'. $this->getPath();
    }

    /**
     * Get absolute path
     *
     * @return string
     */
    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->getPath();
    }

    /**
     * Get web path
     *
     * @return string
     */
    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir().'/'.$this->getPath();
    }

    /**
     * Get upload root directory
     *
     * @return string
     */
    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    /**
     * Get upload directory
     *
     * @return string
     */
    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/'. $this->getSubject()->getId();
    }

    /**
     * Set file
     *
     * @param UploadedFile $file
     * @return Presentation
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        return $this;
    }

    /**
     * Get file
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Presentation
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Upload file
     */
    public function upload()
    {
        // the file property can be empty if the field is not required
        if (null === $this->getFile()) {
            return;
        }

        // use the original file name here but you should
        // sanitize it at least to avoid any security issues

        // move takes the target directory and then the
        // target filename to move to
        $this->getFile()->move(
            $this->getUploadRootDir(),
            $this->getPath()
        );

        // set the path property to the filename where you've saved the file
        $this->path = $this->getFile()->getClientOriginalName();

        // clean up the file property as you won't need it anymore
        $this->file = null;
    }

    /**
     * Set owner
     *
     * @param User $owner
     * @return Presentation
     */
    public function setOwner(User $owner)
    {
        $this->owner = $owner;
        return $this;
    }

    /**
     * Get owner
     *
     * @return User
     */
    public function getOwner()
    {
        return $this->owner;
    }

}