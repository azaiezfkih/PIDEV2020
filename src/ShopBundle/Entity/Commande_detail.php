<?php

namespace ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commande_detail
 *
 * @ORM\Table(name="commande_detail")
 * @ORM\Entity(repositoryClass="ShopBundle\Repository\Commande_detailRepository")
 */
class Commande_detail
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
     * @ORM\ManyToOne(targetEntity="Commande")
     * @ORM\JoinColumn(name="idCommande",referencedColumnName="id")
     */
    private $idCommande;


    /**
     * @ORM\ManyToOne(targetEntity="Produit")
     * @ORM\JoinColumn(name="idProd",referencedColumnName="id")
     */
    private $idProd;

    /**
     * @var int
     *
     * @ORM\Column(name="qte_prod", type="integer")
     */
    private $qteProd;

    /**
     * @var float
     *
     * @ORM\Column(name="prix_prod", type="float", nullable=true)
     */
    private $prixProd;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(name="idUser",referencedColumnName="id")
     */
    private $idClient;

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
     * Set idCommande
     *
     * @param integer $idCommande
     *
     * @return Commande_detail
     */
    public function setIdCommande($idCommande)
    {
        $this->idCommande = $idCommande;

        return $this;
    }

    /**
     * Get idCommande
     *
     * @return int
     */
    public function getIdCommande()
    {
        return $this->idCommande;
    }


    /**
     * Set idProd
     *
     * @param integer $idProd
     *
     * @return Commande_detail
     */
    public function setIdProd($idProd)
    {
        $this->idProd = $idProd;

        return $this;
    }

    /**
     * Get idProd
     *
     * @return int
     */
    public function getIdProd()
    {
        return $this->idProd;
    }

    /**
     * Set qteProd
     *
     * @param integer $qteProd
     *
     * @return Commande_detail
     */
    public function setQteProd($qteProd)
    {
        $this->qteProd = $qteProd;

        return $this;
    }

    /**
     * Get qteProd
     *
     * @return int
     */
    public function getQteProd()
    {
        return $this->qteProd;
    }

    /**
     * Set prixProd
     *
     * @param float $prixProd
     *
     * @return Commande_detail
     */
    public function setPrixProd($prixProd)
    {
        $this->prixProd = $prixProd;

        return $this;
    }

    /**
     * Get prixProd
     *
     * @return float
     */
    public function getPrixProd()
    {
        return $this->prixProd;
    }
    /**
     * Set idClient
     *
     * @param integer $idClient
     *
     * @return Commande
     */
    public function setIdClient($idClient)
    {
        $this->idClient = $idClient;

        return $this;
    }

    /**
     * Get idClient
     *
     * @return int
     */
    public function getIdClient()
    {
        return $this->idClient;
    }
}

