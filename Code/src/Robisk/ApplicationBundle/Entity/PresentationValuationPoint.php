<?php

namespace Robisk\ApplicationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * PresentationValuationPoint
 *
 * @ORM\Table(name="presentation_valuation_point")
 * @ORM\Entity
 */
class PresentationValuationPoint
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
     * @var UserSubjectLookup
     *
     * @ORM\ManyToOne(targetEntity="Subject", inversedBy="presentationValuationPoints")
     * @ORM\JoinColumn(name="subject_id", referencedColumnName="id")
     */
    protected $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="point", type="string", length=255, nullable=true)
     */
    protected $point;

    /**
     * @var integer
     *
     * @ORM\Column(name="height", type="integer", nullable=true)
     */
    protected $height;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="UserPresentationValuation", mappedBy="presentationValuationPoint")
     */
    protected $userPresentationValuation;

    public function __construct()
    {
        $this->userPresentationValuation = new ArrayCollection();
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
     * Set subject
     *
     * @param Subject $subject
     * @return PresentationValuationPoint
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
     * Set point
     *
     * @param string $point
     * @return PresentationValuationPoint
     */
    public function setPoint($point)
    {
        $this->point = $point;
        return $this;
    }

    /**
     * Get point
     *
     * @return string
     */
    public function getPoint()
    {
        return $this->point;
    }

    /**
     * Set height
     *
     * @param integer $height
     * @return PresentationValuationPoint
     */
    public function setHeight($height)
    {
        $this->height = $height;
        return $this;
    }

    /**
     * Get height
     *
     * @return integer
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Add user presentation valuation
     *
     * @param UserPresentationValuation $upValuation
     * @return UserSubjectLookup
     */
    public function addUpValuation(UserPresentationValuation $upValuation)
    {
        $this->userPresentationValuation->add($upValuation);
        return $this;
    }

    /**
     * Remove user presentation valuation
     *
     * @param UserPresentationValuation $upValuation
     * @return UserSubjectLookup
     */
    public function removeUpValuation(UserPresentationValuation $upValuation)
    {
        $this->userPresentationValuation->removeElement($upValuation);
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

}