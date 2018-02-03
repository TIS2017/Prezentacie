<?php

namespace Robisk\ApplicationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Robisk\ApplicationBundle\Entity\UserSubjectLookup;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Robisk\ApplicationBundle\Entity\UserRepository")
 * @UniqueEntity("username")
 * @UniqueEntity("email")
 */
class User implements UserInterface
{
    const ROLE_USER = 1;
    const ROLE_ADMIN = 2;

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
     * @ORM\Column(name="first_name", type="string", length=20)
     * @Assert\NotBlank()
     */
    protected $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=30)
     * @Assert\NotBlank()
     */
    protected $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=30, unique=true)
     * @Assert\NotBlank()
     */
    protected $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min = 6,
     *     minMessage = "Password should by at least 6 chars long"
     * )
     */
    protected $password;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255)
     */
    protected $salt;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     * @Assert\Email()
     * @Assert\NotBlank()
     */
    protected $email;

    /**
     * @var integer
     *
     * @ORM\Column(name="role", type="integer")
     */
    protected $role;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="UserSubjectLookup", mappedBy="user", cascade={"persist", "remove"})
     */
    protected $subjects;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="UserAttendanceLookup", mappedBy="user", cascade={"persist", "remove"})
     */
    protected $userAttendanceLookup;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_visited_announcements", type="datetime", nullable=true)
     */
    protected $lastVisitedAnnouncements;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Presentation", mappedBy="owner", cascade={"persist", "remove"})
     */
    protected $presentationsCreated;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AnnouncementComment", mappedBy="user", cascade={"persist", "remove"})
     */
    protected $announcementComments;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="TeacherPresentationComment", mappedBy="user", cascade={"persist", "remove"})
     */
    protected $teacherPresentationComments;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="UserPresentationComment", mappedBy="user", cascade={"persist", "remove"})
     */
    protected $userPresentationComments;

    public function __construct()
    {
        $this->role = self::ROLE_USER;
        $this->salt = md5(sprintf(
            '%s_%d_%f',
            uniqid(),
            rand(0, 99999),
            microtime(true)
        ));

        $this->subjects = new \Doctrine\Common\Collections\ArrayCollection();
        $this->attendances = new \Doctrine\Common\Collections\ArrayCollection();
        $this->lastVisitedAnnouncements = new \DateTime('1990-01-01 00:00:00');
        $this->presentationsCreated = new \Doctrine\Common\Collections\ArrayCollection();
        $this->announcementComments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->teacherPresentationComments = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set firstName
     *
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * Get first name
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set last name
     *
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * Get last name
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Get full name
     *
     * @return string
     */
    public function getName()
    {
        return $this->firstName .' '. $this->lastName;
    }

    /**
     * Get full name reversed
     *
     * @return string
     */
    public function getNameRev()
    {
        return $this->lastName .' '. $this->firstName;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Get salt
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set role
     *
     * @param integer $role
     * @return User
     */
    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }

    /**
     * Get role
     *
     * @return integer
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Add subject
     *
     * @param Subject $subject
     * @return User
     */
    public function addSubject(Subject $subject)
    {
        $usl = new UserSubjectLookup();
        $usl->setUser($this);
        $usl->setSubject($subject);

        $this->subjects->add($usl);
        return $this;
    }

    /**
     * Remove element
     *
     * @param Subject $subject
     * @return User
     */
    public function removeSubject(Subject $subject)
    {
        $this->subjects->removeElement($subject);
        return $this;
    }

    /**
     * Get all subjects
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubjects()
    {
        return $this->subjects;
    }

    /**
     * Set all subjects
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $subjects
     * @return User
     */
    public function setSubjects($subjects)
    {
        $this->subjects = $subjects;
        return $this;
    }

    /**
     * Add user attendance lookup
     *
     * @param UserAttendanceLookup $userAttendanceLookup
     * @return User
     */
    public function addUserAttendanceLookup(UserAttendanceLookup $userAttendanceLookup)
    {
        $this->userAttendanceLookup->add($userAttendanceLookup);
        return $this;
    }

    /**
     * Remove user attendance lookup
     *
     * @param UserAttendanceLookup $userAttendanceLookup
     * @return User
     */
    public function removeUserAttendanceLookup(UserAttendanceLookup $userAttendanceLookup)
    {
        $this->userAttendanceLookup->removeElement($userAttendanceLookup);
        return $this;
    }

    /**
     * Get user attendance lookups
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getUserAttendanceLookup()
    {
        return $this->userAttendanceLookup;
    }

    /**
     * Get roles
     *
     * @return array
     */
    public function getRoles()
    {
        if ($this->role == self::ROLE_USER) {
            return array('ROLE_USER');
        }
        if ($this->role == self::ROLE_ADMIN) {
            return array('ROLE_ADMIN');
        }
    }

    /**
     * Erase credentials
     */
    public function eraseCredentials()
    {

    }

    /**
     * Get subjects from usl
     *
     * @return array
     */
    public function getMySubjects()
    {
        $subjects = array();
        foreach ($this->subjects as $usl) {
            $subjects[] = $usl->getSubject();
        }

        return $subjects;
    }

    /**
     * Get my approved subjects
     *
     * @return array
     */
    public function getMyApprovedSubjects()
    {
        $subjects = array();
        foreach ($this->subject as $usl) {
            if ($usl->getStatus() == UserSubjectLookup::STATUS_APPROVED) {
                $subjects[] = $usl->getSubject();
            }
        }

        return $subjects;
    }

    /**
     * Get my unapproved subjects
     *
     * @return array
     */
    public function getMyUnapprovedSubjects()
    {
        $subjects = array();
        foreach ($this->subject as $usl) {
            if ($usl->getStatus() == UserSubjectLookup::STATUS_UNAPPROVED) {
                $subjects[] = $usl->getSubject();
            }
        }

        return $subjects;
    }

    /**
     * Get my rejected subjects
     *
     * @return array
     */
    public function getMyRejectedSubjects()
    {
        $subjects = array();
        foreach ($this->subject as $usl) {
            if ($this->getStatus() == UserSubjectLookup::STATUS_REJECTED) {
                $subjects[] = $usl->getSubject();
            }
        }

        return $subjects;
    }

    /**
     * Get attendance from ual
     *
     * @return array
     */
    public function getMyAttendances()
    {
        $attendances = array();
        foreach ($this->userAttendanceLookup as $ual) {
            $attendances[] = $ual->getAttendance();
        }

        return $attendances;
    }

    /**
     * Get attendance done statement
     *
     * @param Attendance $attendance
     * @return boolean
     */
    public function getAttendanceDone(Attendance $attendance)
    {
        foreach ($this->userAttendanceLookup as $ual) {
            if ($ual->getAttendance() == $attendance) {
                return true;
            }
        }

        return false;
    }

    /**
     * Set last visited announcements
     *
     * @param \DateTime $lastVisit
     * @return User
     */
    public function setLastVisitedAnnouncements($lastVisit)
    {
        $this->lastVisitedAnnouncements = $lastVisit;
        return $this;
    }

    /**
     * Get last visited announcements
     *
     * @return \DateTime
     */
    public function getLastVisitedAnnouncements()
    {
        return $this->lastVisitedAnnouncements;
    }

    /**
     * Get presentation in subject
     *
     * @param integer $subjectId
     * @return Presentation
     */
    public function getPresentation($subjectId)
    {
        foreach ($this->subjects as $usl) {
            if ($usl->getSubject()->getId() == $subjectId) {
                $presentation = $usl->getPresentation();
            }
        }

        return $presentation;
    }

    /**
     * Add presentation created
     *
     * @param Presentation $presentation
     * @return User
     */
    public function addPresentationsCreated(Presentation $presentation)
    {
        $this->presentationsCreated->add($presentation);
        return $this;
    }

    /**
     * Remove presentation created
     *
     * @param Presentation $presentation
     * @return User
     */
    public function removePresentationsCreated(Presentation $presentation)
    {
        $this->presentationsCreated->removeElement($presentation);
        return $this;
    }

    /**
     * Get presentations created
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getPresentationsCreated()
    {
        return $this->presentationsCreated;
    }

    /**
     * Has attendance?
     *
     * @param Attendance $attendance
     * @return boolean
     */
    public function hasAttendance(Attendance $attendance)
    {
        foreach ($this->userAttendanceLookup as $ual) {
            if ($attendance == $ual->getAttendance()) {
                return true;
            }
        }

        return false;
    }
}