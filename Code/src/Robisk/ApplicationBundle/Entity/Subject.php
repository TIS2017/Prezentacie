<?php

namespace Robisk\ApplicationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Subject
 *
 * @ORM\Table(name="subject")
 * @ORM\Entity(repositoryClass="Robisk\ApplicationBundle\Entity\SubjectRepository")
 */
class Subject
{
    const SEASON_SUMMER = 1;
    const SEASON_WINTER = 2;

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;

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
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="year", type="string", length=9)
     * @Assert\NotBlank()
     */
    protected $schoolYear;

    /**
     * @var integer
     *
     * @ORM\Column(name="season", type="integer")
     * @Assert\NotBlank()
     */
    protected $season;

    /**
     * @var string
     *
     * @ORM\Column(name="about", type="text", nullable=true)
     */
    protected $about;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer")
     */
    protected $status;

    /**
     * @var SubjectValuation
     *
     * @ORM\OneToOne(targetEntity="SubjectValuation", mappedBy="subject", cascade={"persist", "remove"})
     */
    protected $valuation;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Announcement", mappedBy="subject", cascade={"persist", "remove"})
     */
    protected $announcements;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="UserSubjectLookup", mappedBy="subject", cascade={"persist", "remove"})
     */
    protected $users;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_limit", type="integer")
     */
    protected $userLimit;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Attendance", mappedBy="subject", cascade={"persist", "remove"})
     */
    protected $attendances;

    /**
     * @var integer
     *
     * @ORM\Column(name="weeks", type="integer")
     */
    protected $weeks;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="PresentationValuationPoint", mappedBy="subject")
     */
    protected $presentationValuationPoints;

    /**
     * @var integer
     *
     * @ORM\Column(name="val_attendance", type="integer", options={"default"=30})
     */
    protected $valAttendance;

    /**
     * @var integer
     *
     * @ORM\Column(name="val_presentation", type="integer", options={"default"=70})
     */
    protected $valPresentation;

    public function __construct()
    {
        $this->status = self::STATUS_INACTIVE;
        $this->users = new ArrayCollection();
        $this->announcements = new ArrayCollection();
        $this->attendances = new ArrayCollection();
        $this->presentationValuationPoints = new ArrayCollection();
        $this->valAttendance = 30;
        $this->valPresentation = 70;
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
     * Set name
     *
     * @param string $name
     * @return Subject
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set school year
     *
     * @param string $schoolYear
     * @return Subject
     */
    public function setSchoolYear($schoolYear)
    {
        $this->schoolYear = $schoolYear;
        return $this;
    }

    /**
     * Get school year
     *
     * @return string
     */
    public function getSchoolYear()
    {
        return $this->schoolYear;
    }

    /**
     * Set season
     *
     * @param integer $season
     * @return Subject
     */
    public function setSeason($season)
    {
        $this->season = $season;
        return $this;
    }

    /**
     * Get season
     *
     * @return integer
     */
    public function getSeason()
    {
        return $this->season;
    }

    /**
     * Set about
     *
     * @param string $about
     * @return Subject
     */
    public function setAbout($about)
    {
        $this->about = $about;
        return $this;
    }

    /**
     * Get about
     *
     * @return string
     */
    public function getAbout()
    {
        return $this->about;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Subject
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $status;
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
     * Set valuation
     *
     * @param SubjectValuation $valuation
     * @return Subject
     */
    public function setValuation(SubjectValuation $valuation)
    {
        $this->valuation = $valuation;
        return $this;
    }

    /**
     * Get valuation
     *
     * @return SubjectValuation
     */
    public function getValuation()
    {
        return $this->valuation;
    }

    /**
     * Add announcement
     *
     * @param Announcement $announcement
     * @return Subject
     */
    public function addAnnouncement(Announcement $announcement)
    {
        $this->announcements->add($announcement);
        return $this;
    }

    /**
     * Remove announcement
     *
     * @param Announcement $announcement
     * @return Subject
     */
    public function removeAnnouncement(Announcement $announcement)
    {
        $this->announcements->removeElement($announcement);
        return $this;
    }

    /**
     * Get all announcements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAnnouncements()
    {
        return $this->announcements;
    }

    /**
     * Add user
     *
     * @param User $user
     * @return Subject
     */
    public function addUser(User $user)
    {
        $usl = new UserSubjectLookup();
        $usl->setSubject($this);
        $usl->setUser($user);

        $this->users->add($usl);
        return $this;
    }

    /**
     * Remove user
     *
     * @param User $user
     * @return Subject
     */
    public function removeUser(User $user)
    {
        $this->users->removeElement($user);
        return $this;
    }

    /**
     * Get all users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Set all users
     *
     * @param \Doctrine\Common\Collections\Collection $users
     * @return Subject
     */
    public function setUsers($users)
    {
        $this->users = $users;
        return $this;
    }

    /**
     * Sort array of objects by field.
     *
     * @param array $objects Array of objects to sort.
     * @param string $onClass Name of field.
     * @param string $order (ASC|DESC)
     */
    private function sortOnField(&$objects, $onClass, $order = 'ASC')
    {
        $comparer = ($order === 'DESC')
            ? "return -strcmp(\$a->{$onClass},\$b->{$onClass});"
            : "return strcmp(\$a->{$onClass},\$b->{$onClass});";
        usort($objects, create_function('$a,$b', $comparer));
    }

    /**
     * Get approved users
     *
     * @return array
     */
    public function getApprovedUsers()
    {
        $users = array();
        foreach ($this->users as $usl) {
            if ($usl->getStatus() == UserSubjectLookup::STATUS_APPROVED) {
                $users[] = $usl->getUser();
            }
        }

        $this->sortOnField($users, 'getNameRev()', 'ASC');

        return $users;
    }

    /**
     * Get unapproved users
     *
     * @return array
     */
    public function getUnapprovedUsers()
    {
        $users = array();
        foreach ($this->users as $usl) {
            if ($usl->getStatus() == UserSubjectLookup::STATUS_UNAPPROVED) {
                $users[] = $usl->getUser();
            }
        }

        $this->sortOnField($users, 'getNameRev()', 'ASC');

        return $users;
    }

    /**
     * Get declined users
     *
     * @return array
     */
    public function getDeclinedUsers()
    {
        $users = array();
        foreach ($this->users as $usl) {
            if ($usl->getStatus() == UserSubjectLookup::STATUS_DECLINED) {
                $users[] = $usl->getUser();
            }
        }

        $this->sortOnField($users, 'getNameRev()', 'ASC');

        return $users;
    }

    /**
     * Set user limit
     *
     * @param integer $userLimit
     * @return Subject
     */
    public function setUserLimit($userLimit)
    {
        $this->userLimit = $userLimit;
        return $this;
    }

    /**
     * Get user limit
     *
     * @return integer
     */
    public function getUserLimit()
    {
        return $this->userLimit;
    }

    /**
     * Add attendance
     *
     * @param Attendance $attendance
     * @return Subject
     */
    public function addAttendance(Attendance $attendance)
    {
        $this->attendances->add($attendance);
        return $this;
    }

    /**
     * Remove attendance
     *
     * @param Attendance $attendance
     * @return Subject
     */
    public function removeAttendance(Attendance $attendance)
    {
        $this->attendances->removeElement($attendance);
        return $this;
    }

    /**
     * Get attendances
     *
     * @return ArrayCollection
     */
    public function getAttendances()
    {
        return $this->attendances;
    }

    /**
     * Get unlocked attendance
     *
     * @return Attendance
     */
    public function getUnlockedAttendance()
    {
        foreach ($this->attendances as $attendance) {
            if ($attendance->getStatus() == Attendance::STATUS_UNLOCKED) {
                return $attendance;
            }
        }

        return null;
    }

    /**
     * Set weeks
     *
     * @param integer $weeks
     * @return Subject
     */
    public function setWeeks($weeks)
    {
        $this->weeks = $weeks;
        return $this;
    }

    /**
     * Get weeks
     *
     * @return integer
     */
    public function getWeeks()
    {
        return $this->weeks;
    }

    /**
     * Get presentation from user
     *
     * @param integer $userId
     * @return Presentation
     */
    public function getPresentation($userId)
    {
        $presentation = null;
        foreach ($this->users as $usl) {
            if ($usl->getUser()->getId() == $userId) {
                $presentation = $usl->getPresentation();
            }
        }

        return $presentation;
    }

    /**
     * Get all presentations
     *
     * @return array
     */
    public function getPresentations()
    {
        $presentations = array();
        foreach ($this->users as $usl) {
            if ($usl->getPresentation() != null) {
                $presentations[] = $usl->getPresentation();
            }
        }

        return $presentations;
    }

    /**
     * Get presenting presentations
     *
     * @return array
     */
    public function getPresentingPresentations()
    {
        $presentations = array();
        foreach ($this->getPresentations() as $presentation) {
            if ($presentation->getStatus() == 1) {
                if (! in_array($presentation, $presentations)) {
                    $presentations[] = $presentation;
                }
            }
        }

        return $presentations;
    }

    /**
     * Add presentation valuation point
     *
     * @param PresentationValuationPoint $pvPoint
     * @return UserSubjectLookup
     */
    public function addPvPoint(PresentationValuationPoint $pvPoint)
    {
        $this->presentationValuationPoints->add($pvPoint);
        return $this;
    }

    /**
     * Remove presentation valuation point
     *
     * @param PresentationValuationPoint $pvPoint
     * @return UserSubjectLookup
     */
    public function removePvPoint(PresentationValuationPoint $pvPoint)
    {
        $this->presentationValuationPoints->removeElement($pvPoint);
        return $this;
    }

    /**
     * Get presentation valuation points
     *
     * @return ArrayCollection
     */
    public function getPresentationValuationPoints()
    {
        return $this->presentationValuationPoints;
    }

    /**
     * Set attendance valuation
     *
     * @param integer $valAttendance
     * @return Subject
     */
    public function setValAttendance($valAttendance)
    {
        $this->valAttendance = $valAttendance;
        return $this;
    }

    /**
     * Get attendance valuation
     *
     * @return integer
     */
    public function getValAttendance()
    {
        return $this->valAttendance;
    }

    /**
     * Set presentation valuation
     *
     * @param integer $valPresentation
     * @return Subject
     */
    public function setValPresentation($valPresentation)
    {
        $this->valPresentation = $valPresentation;
        return $this;
    }

    /**
     * Get presentation valuation
     *
     * @return integer
     */
    public function getValPresentation()
    {
        return $this->valPresentation;
    }

    /**
     * Get sum of presentation and attendance valuation
     *
     * @return integer
     */
    public function getValSum()
    {
        return $this->valPresentation + $this->valAttendance;
    }
}
