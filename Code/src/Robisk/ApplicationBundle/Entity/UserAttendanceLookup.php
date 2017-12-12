<?php

namespace Robisk\ApplicationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * UserAttendanceLookup
 *
 * @ORM\Table(name="user_attendance_lookup")
 * @ORM\Entity
 */
class UserAttendanceLookup
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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="userAttendanceLookup")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var Subject
     *
     * @ORM\ManyToOne(targetEntity="Attendance", inversedBy="users")
     */
    protected $attendance;

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
     * Set user
     *
     * @param User $user
     * @return UserAttendanceLookup
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
     * Set attendance
     *
     * @param Attendance $attendance
     * @return UserAttendanceLookup
     */
    public function setAttendance(Attendance $attendance)
    {
        $this->attendance = $attendance;
        return $this;
    }

    /**
     * Get attendance
     *
     * @return Subject
     */
    public function getAttendance()
    {
        return $this->attendance;
    }
}
