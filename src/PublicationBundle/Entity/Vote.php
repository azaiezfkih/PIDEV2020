<?php

namespace PublicationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Vote
 *
 * @ORM\Table(name="vote")
 * @ORM\Entity(repositoryClass="PublicationBundle\Repository\VoteRepository")
 */
class Vote
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
     * @var int
     *
     * @ORM\Column(name="dislikeReaction", type="integer")
     */
    private $dislikeReaction;


    /**
     * @ORM\ManyToOne(targetEntity="PublicationBundle\Entity\Publication")
     * @ORM\JoinColumn(name="id_publication",referencedColumnName="id")
     */
    private $id_publication;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(name="id_user",referencedColumnName="id")
     */
    private $id_user;


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
     * Set dislikeReaction
     *
     * @param integer $dislikeReaction
     *
     * @return Vote
     */
    public function setDislikeReaction($dislikeReaction)
    {
        $this->dislikeReaction = $dislikeReaction;

        return $this;
    }

    /**
     * Get dislikeReaction
     *
     * @return int
     */
    public function getDislikeReaction()
    {
        return $this->dislikeReaction;
    }

    /**
     * @return mixed
     */
    public function getIdPublication()
    {
        return $this->id_publication;
    }

    /**
     * @param mixed $id_publication
     */
    public function setIdPublication($id_publication)
    {
        $this->id_publication = $id_publication;
    }

    /**
     * @return mixed
     */
    public function getIdUser()
    {
        return $this->id_user;
    }

    /**
     * @param mixed $id_user
     */
    public function setIdUser($id_user)
    {
        $this->id_user = $id_user;
    }


}

