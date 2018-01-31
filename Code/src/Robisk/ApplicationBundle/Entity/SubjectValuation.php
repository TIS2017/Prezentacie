<?php

namespace Robisk\ApplicationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Robisk\ApplicationBundle\Entity\Episode as Episode;

/**
 * SubjectValuation
 *
 * @ORM\Table(name="subject_valuation")
 * @ORM\Entity
 */
class SubjectValuation
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
     * @ORM\OneToOne(targetEntity="Subject", inversedBy="valuation")
     * @ORM\JoinColumn(name="subject_id", referencedColumnName="id")
     */
    protected $subject;

    /**
     * @var integer
     *
     * @ORM\Column(name="A", type="integer")
     * @Assert\NotBlank()
     */
    protected $A;

    /**
     * @var integer
     *
     * @ORM\Column(name="B", type="integer")
     * @Assert\NotBlank()
     */
    protected $B;

    /**
     * @var integer
     *
     * @ORM\Column(name="C", type="integer")
     * @Assert\NotBlank()
     */
    protected $C;

    /**
     * @var integer
     *
     * @ORM\Column(name="D", type="integer")
     * @Assert\NotBlank()
     */
    protected $D;

    /**
     * @var integer
     *
     * @ORM\Column(name="E", type="integer")
     * @Assert\NotBlank()
     */
    protected $E;

    /**
     * @var integer
     *
     * @ORM\Column(name="Fx", type="integer")
     * @Assert\NotBlank()
     */
    protected $Fx;

    public function __construct()
    {
        $this->A = 100;
        $this->B = 90;
        $this->C = 80;
        $this->D = 70;
        $this->E = 60;
        $this->Fx = 50;
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
     * @return SubjectValuation
     */
    public function setSubject($subject)
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
     * Set ceiling valuation of subject for A
     *
     * @param integer $a
     * @return SubjectValuation
     */
    public function setA($a)
    {
        $this->A = $a;
        return $this;
    }

    /**
     * Get ceiling valuation of subject for A
     *
     * @return integer
     */
    public function getA()
    {
        return $this->A;
    }

    /**
     * Set ceiling valuation of subject for B
     *
     * @param integer $b
     * @return SubjectValuation
     */
    public function setB($b)
    {
        $this->B = $b;
        return $this;
    }

    /**
     * Get ceiling valuation of subject for B
     *
     * @return integer
     */
    public function getB()
    {
        return $this->B;
    }

    /**
     * Set ceiling valuation of subject for C
     *
     * @param integer $c
     * @return SubjectValuation
     */
    public function setC($c)
    {
        $this->C = $c;
        return $this;
    }

    /**
     * Get ceiling valuation of subject for C
     *
     * @return integer
     */
    public function getC()
    {
        return $this->C;
    }

    /**
     * Set ceiling valuation of subject for D
     *
     * @param integer $d
     * @return SubjectValuation
     */
    public function setD($d)
    {
        $this->D = $d;
        return $this;
    }

    /**
     * Get ceiling valuation of subject for D
     *
     * @return integer
     */
    public function getD()
    {
        return $this->D;
    }

    /**
     * Set ceiling valuation of subject for E
     *
     * @param integer $e
     * @return SubjectValuation
     */
    public function setE($e)
    {
        $this->E = $e;
        return $this;
    }

    /**
     * Get ceiling valuation of subject for E
     *
     * @return integer
     */
    public function getE()
    {
        return $this->E;
    }

    /**
     * Set ceiling valuation of subject for Fx
     *
     * @param integer $fx
     * @return SubjectValuation
     */
    public function setFx($fx)
    {
        $this->Fx = $fx;
        return $this;
    }

    /**
     * Get ceiling valuation of subject for Fx
     *
     * @return integer
     */
    public function getFx()
    {
        return $this->Fx;
    }
}
