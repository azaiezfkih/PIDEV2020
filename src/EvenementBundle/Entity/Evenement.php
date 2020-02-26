<?php

namespace EvenementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Evenement
 *
 * @ORM\Table(name="evenement")
 * @ORM\Entity(repositoryClass="EvenementBundle\Repository\EvenementRepository")
 */
class Evenement
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
     * @var string
     *
     * @ORM\Column(name="nomEvent", type="string", length=255)
     */
    private $nomEvent;

    /**
     * @var string
     *
     * @ORM\Column(name="gratuite", type="string", length=255)
     */
    private $gratuite;

    /**
     * @var string
     *
     * @ORM\Column(name="theme", type="string", length=255)
     */
    private $theme;

    /**
     * @var string
     *
     * @ORM\Column(name="addresse", type="string", length=255)
     */
    private $addresse;

    /**
     * @var \DateTime
     *
     * @Assert\GreaterThan("now")
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var int
     * @Assert\GreaterThan(
     *     value = 0
     *     )
     * @ORM\Column(name="nbrParticipant", type="integer")
     */
    private $nbrParticipant;

    /**
     * @var int
     * @Assert\GreaterThan(
     *     value = -1
     *     )
     * @ORM\Column(name="prix", type="integer")
     */
    private $prix=0;




    /**
     * @var \DateTime
     *
     *
     * @ORM\Column(name="dateDebut", type="time")
     */
    private $dateDebut;

    /**
     * @var \DateTime
     *
     *
     * @ORM\Column(name="DateFin", type="time")
     */
    private $dateFin;

    /**
     * @var string
     *
     * @ORM\Column(name="Description", type="text")
     */
    private $description;




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
     * Set nomEvent
     *
     * @param string $nomEvent
     *
     * @return Evenement
     */
    public function setNomEvent($nomEvent)
    {
        $this->nomEvent = $nomEvent;

        return $this;
    }

    /**
     * Get nomEvent
     *
     * @return string
     */
    public function getNomEvent()
    {
        return $this->nomEvent;
    }

    /**
     * Set theme
     *
     * @param string $theme
     *
     * @return Evenement
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * Get theme
     *
     * @return string
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * Set addresse
     *
     * @param string $addresse
     *
     * @return Evenement
     */
    public function setAddresse($addresse)
    {
        $this->addresse = $addresse;

        return $this;
    }

    /**
     * Get addresse
     *
     * @return string
     */
    public function getAddresse()
    {
        return $this->addresse;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Evenement
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set nbrParticipant
     *
     * @param integer $nbrParticipant
     *
     * @return Evenement
     */
    public function setNbrParticipant($nbrParticipant)
    {
        $this->nbrParticipant = $nbrParticipant;

        return $this;
    }

    /**
     * Get nbrParticipant
     *
     * @return int
     */
    public function getNbrParticipant()
    {
        return $this->nbrParticipant;
    }

    /**
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     *
     * @return Evenement
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \DateTime
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     *
     * @return Evenement
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \DateTime
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Evenement
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getGratuite()
    {
        return $this->gratuite;
    }

    /**
     * @param string $gratuite
     */
    public function setGratuite($gratuite)
    {
        $this->gratuite = $gratuite;
    }

    /**
     * @return int
     */
    public function getPrix()
    {
        return $this->prix;
    }



}

