<?php

namespace Robisk\ApplicationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * UserSubjectLookup
 *
 * @ORM\Table(name="user_subject_lookup")
 * @ORM\Entity
 */
class UserSubjectLookup
{
    const STATUS_UNAPPROVED = 1;
    const STATUS_APPROVED = 2;
    const STATUS_DECLINED = 3;

    const MAX_PRESENTATION_POINTS = 10;

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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="subjects")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var Subject
     *
     * @ORM\ManyToOne(targetEntity="Subject", inversedBy="users")
     * @ORM\JoinColumn(name="subject_id", referencedColumnName="id")
     */
    protected $subject;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer")
     */
    protected $status;

    /**
     * @var Presentation
     *
     * @ORM\ManyToOne(targetEntity="Presentation", inversedBy="userSubjectLookup", fetch="EAGER")
     * @ORM\JoinColumn(name="presentation_id", referencedColumnName="id", nullable=true)
     */
    protected $presentation;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="UserPresentationValuation", mappedBy="whoseUsl")
     */
    protected $valuatingPresentations;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="UserPresentationValuation", mappedBy="targetUsl")
     */
    protected $userPresentationValuation;

    public function __construct()
    {
        $this->status = self::STATUS_UNAPPROVED;
        $this->valuatingPresentations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->userPresentationValuation = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set user
     *
     * @param User $user
     * @return UserSubjectLookup
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set subject
     *
     * @param Subject $subject
     * @return UserSubjectLookup
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
     * Set status
     *
     * @param integer $status
     * @return UserSubjectLookup
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
     * Set presentation
     *
     * @param Presentation $presentation
     * @return UserSubjectLookup
     */
    public function setPresentation(Presentation $presentation = null)
    {
        $this->presentation = $presentation;
        return $this;
    }

    /**
     * Get presentation
     *
     * @return Presentation
     */
    public function getPresentation()
    {
        return $this->presentation;
    }

    /**
     * Add valuating presentations
     *
     * @param UserPresentationValuation $userPresentationValuation
     * @return UserSubjectLookup
     */
    public function addValuatingPresentations(UserPresentationValuation $userPresentationValuation)
    {
        $this->valuatingPresentations->add($userPresentationValuation);
        return $this;
    }

    /**
     * Remove valuating presentations
     *
     * @param UserPresentationValuation $userPresentationValuation
     * @return UserSubjectLookup
     */
    public function removeValuatingPresentations(UserPresentationValuation $userPresentationValuation)
    {
        $this->valuatingPresentations->removeElement($userPresentationValuation);
        return $this;
    }

    /**
     * Get valuating presentations
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getValuatingPresentations()
    {
        return $this->valuatingPresentations;
    }

    /**
     * Add user presentation valuation
     *
     * @param UserPresentationValuation $userPresentationValuation
     * @return UserSubjectLookup
     */
    public function addUserPresentationValuation(UserPresentationValuation $userPresentationValuation)
    {
        $this->userPresentationValuation->add($userPresentationValuation);
        return $this;
    }

    /**
     * Remove user presentation valuation
     *
     * @param UserPresentationValuation $userPresentationValuation
     * @return UserSubjectLookup
     */
    public function removeUserPresentationValuation(UserPresentationValuation $userPresentationValuation)
    {
        $this->userPresentationValuation->removeElement($userPresentationValuation);
        return $this;
    }

    /**
     * Get user presentation valuation
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getUserPresentationValuation()
    {
        return $this->userPresentationValuation;
    }

    /**
     * Is user presentation valuated?
     *
     * @param integer $userId
     * @param integer $subjectId
     * @return boolean
     */
    public function isUserPresentationValuated($userId, $subjectId)
    {
        $result = false;
        foreach ($this->valuatingPresentations as $vp) {
            if (($vp->getTargetUsl()->getUser()->getId() == $userId) &&
                ($vp->getTargetUsl()->getSubject()->getId() == $subjectId)) {
                $result = true;
            }
        }

        return $result;
    }

    /**
     * Get points for attendance
     *
     * @return integer
     */
    public function getAttendancePoints()
    {
        $att =$this->getSubject()->getAttendances();
        if (empty($att)) {
            return 0;
        }

        $points = 0;
        foreach ($this->getSubject()->getAttendances() as $attendance) {
            if (in_array($attendance, $this->getUser()->getMyAttendances())) {
                $points++;
            }
        }

        $temp = (100/count($att))*$points;
        $result = ($this->getSubject()->getValAttendance()/100)*$temp;

        return $result;
    }

    /**
     * Get points for comments
     *
     * @return integer
     */
    public function getCommentPoints()
    {
        $ann =$this->getSubject()->getAnnouncements();
        if (count($ann) == 0 ) {
            return 0;
        }

        $points = 0;
        foreach ($ann as $announcement) {
            foreach ($announcement->getComments() as $comment){
                if (($comment->getUser()->getId() == $this->getUser()->getId()) && ($comment->getValuated())){
                    $points++;
                }
            }
        }

        $temp = (100/count($ann))*$points;
        $result = ($this->getSubject()->getValComment()/100)*$temp;

        return $result;
    }

    /**
     * Get points for presentation
     *
     * @return integer
     */
    public function getPresentationPoints()
    {
        $points = 0;
        foreach ($this->getSubject()->getPresentationValuationPoints() as $pvp) {
            $pvpPoints = 0;
            $max = 0;
            foreach ($this->getUserPresentationValuation() as $upv) {
                if ($upv->getPresentationValuationPoint() == $pvp) {
                    $pvpPoints += $upv->getPoints();
                    $max += self::MAX_PRESENTATION_POINTS;
                }
            }

            if ($max == 0) {
                return 0;
            }
            
            $temp = (100/$max)*$pvpPoints;
            $points += ($pvp->getHeight()/100)*$temp;
        }

        $result = $points*($this->getSubject()->getValPresentation()/100);

        return $result;
    }

    /**
     * Get points counts
     *
     * @return integer
     */
    public function getPointsCount()
    {
        return $this->getAttendancePoints() + $this->getPresentationPoints() + $this->getCommentPoints();
    }

    /**
     * Get mark
     *
     * @return string
     */
    public function getMark()
    {
        $points = $this->getPointsCount();
        if ($points >= $this->getSubject()->getValuation()->getB()) {
            return "A";
        } else {
            if ($points >= $this->getSubject()->getValuation()->getC()) {
                return "B";
            } else {
                if ($points >= $this->getSubject()->getValuation()->getD()) {
                    return "C";
                } else {
                    if ($points >= $this->getSubject()->getValuation()->getE()) {
                        return "D";
                    } else {
                        if ($points >= $this->getSubject()->getValuation()->getFx()) {
                            return "E";
                        } else {
                            return "Fx";
                        }
                    }
                }
            }
        }
    }
}
