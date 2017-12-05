<?php

namespace Robisk\ApplicationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * UserPresentationValuation
 *
 * @ORM\Table(name="user_presentation_valuation")
 * @ORM\Entity
 */
class UserPresentationValuation
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
     * @ORM\ManyToOne(targetEntity="UserSubjectLookup", inversedBy="valuatingPresentations")
     * @ORM\JoinColumn(name="whose_usl_id", referencedColumnName="id")
     */
    protected $whoseUsl;

    /**
     * @var UserSubjectLookup
     *
     * @ORM\ManyToOne(targetEntity="UserSubjectLookup", inversedBy="userPresentationValuation")
     * @ORM\JoinColumn(name="target_usl_id", referencedColumnName="id")
     */
    protected $targetUsl;

    /**
     * @var PresentationValuationPoint
     *
     * @ORM\ManyToOne(targetEntity="PresentationValuationPoint", inversedBy="userPresentationValuation")
     * @ORM\JoinColumn(name="pvp_id", referencedColumnName="id")
     */
    protected $presentationValuationPoint;

    /**
     * @var integer
     *
     * @ORM\Column(name="points", type="integer")
     */
    protected $points;

    public function __construct()
    {

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
     * Set whose user subject lookup
     *
     * @param UserSubjectLookup $whoseUsl
     * @return UserPresentationValuation
     */
    public function setWhoseUsl(UserSubjectLookup $whoseUsl)
    {
        $this->whoseUsl = $whoseUsl;
        return $this;
    }

    /**
     * Get whose user subject lookup
     *
     * @return UserSubjectLookup
     */
    public function getWhoseUsl()
    {
        return $this->whoseUsl;
    }

    /**
     * Set target user subject lookup
     *
     * @param UserSubjectLookup $targetUsl
     * @return UserPresentationValuation
     */
    public function setTargetUsl(UserSubjectLookup $targetUsl)
    {
        $this->targetUsl = $targetUsl;
        return $this;
    }

    /**
     * Get target user subject lookup
     *
     * @return UserSubjectLookup
     */
    public function getTargetUsl()
    {
        return $this->targetUsl;
    }

    /**
     * Set presentation valuation point
     *
     * @param PresentationValuationPoint $presentationValuationPoint
     * @return UserPresentationValuation
     */
    public function setPresentationValuationPoint(PresentationValuationPoint $presentationValuationPoint)
    {
        $this->presentationValuationPoint = $presentationValuationPoint;
        return $this;
    }

    /**
     * Get presentation valuation point
     *
     * @return PresentationValuationPoint
     */
    public function getPresentationValuationPoint()
    {
        return $this->presentationValuationPoint;
    }

    /**
     * Set points
     *
     * @param integer $points
     * @return UserPresentationValuation
     */
    public function setPoints($points)
    {
        $this->points = $points;
        return $this;
    }

    /**
     * Get points
     *
     * @return integer
     */
    public function getPoints()
    {
        return $this->points;
    }

}