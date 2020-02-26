<?php

namespace EvenementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Participation
 *
 * @ORM\Table(name="participation")
 * @ORM\Entity(repositoryClass="EvenementBundle\Repository\ParticipationRepository")
 */
class Participation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * Get id
     *
     * @return int
     */
    /**
     *@ORM\ManyToOne(targetEntity="Evenement")
     *
     * @ORM\JoinColumn(name="evenement_id", referencedColumnName="id", onDelete="CASCADE")
     */

    public $evenement_id;
    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     *
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */

    public $user_id;



    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateParticipation", type="datetime")
     */
    private $dateParticipation;

    /**
     * @var string
     *
     * @ORM\Column(name="maileParticipant", type="string", length=255)
     */
    private $maileParticipant;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=255)
     */
    private $role;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set dateParticipation
     *
     * @param \DateTime $dateParticipation
     *
     * @return Participation
     */
    public function setDateParticipation($dateParticipation)
    {
        $this->dateParticipation = $dateParticipation;

        return $this;
    }

    /**
     * Get dateParticipation
     *
     * @return \DateTime
     */
    public function getDateParticipation()
    {
        return $this->dateParticipation;
    }

    /**
     * Set maileParticipant
     *
     * @param string $maileParticipant
     *
     * @return Participation
     */
    public function setMaileParticipant($maileParticipant)
    {
        $this->maileParticipant = $maileParticipant;

        return $this;
    }

    /**
     * Get maileParticipant
     *
     * @return string
     */
    public function getMaileParticipant()
    {
        return $this->maileParticipant;
    }

    /**
     * Set role
     *
     * @param string $role
     *
     * @return Participation
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }
    /**
     *
     * @return mixed
     */
    public function getEvenementId()
    {
        return $this->evenement_id;
    }

    /**
     * @param mixed $evenement_id
     */
    public function setEvenementId($evenement_id)
    {
        $this->evenement_id = $evenement_id;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }
}

