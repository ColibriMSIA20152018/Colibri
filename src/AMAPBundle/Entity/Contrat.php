<?php

namespace AMAPBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Contrat
 *
 * @ORM\Table(name="contrat")
 * @ORM\Entity(repositoryClass="AMAPBundle\Repository\ContratRepository")
 */
class Contrat
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
    * @ORM\ManyToOne(targetEntity="AMAPBundle\Entity\Acteur")
    */
    private $consommateur;
    
    /**
    * @ORM\ManyToOne(targetEntity="AMAPBundle\Entity\Acteur")
    */
    private $producteur;
    
    /**
    * @ORM\ManyToOne(targetEntity="AMAPBundle\Entity\Panier")
    */
    private $panier;
    
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
     * Set consommateur
     *
     * @param \AMAPBundle\Entity\Acteur $consommateur
     *
     * @return Contrat
     */
    public function setConsommateur(\AMAPBundle\Entity\Acteur $consommateur = null)
    {
        $this->consommateur = $consommateur;

        return $this;
    }

    /**
     * Get consommateur
     *
     * @return \AMAPBundle\Entity\Acteur
     */
    public function getConsommateur()
    {
        return $this->consommateur;
    }

    /**
     * Set producteur
     *
     * @param \AMAPBundle\Entity\Acteur $producteur
     *
     * @return Contrat
     */
    public function setProducteur(\AMAPBundle\Entity\Acteur $producteur = null)
    {
        $this->producteur = $producteur;

        return $this;
    }

    /**
     * Get producteur
     *
     * @return \AMAPBundle\Entity\Acteur
     */
    public function getProducteur()
    {
        return $this->producteur;
    }

    /**
     * Set panier
     *
     * @param \AMAPBundle\Entity\Panier $panier
     *
     * @return Contrat
     */
    public function setPanier(\AMAPBundle\Entity\Panier $panier = null)
    {
        $this->panier = $panier;

        return $this;
    }

    /**
     * Get panier
     *
     * @return \AMAPBundle\Entity\Panier
     */
    public function getPanier()
    {
        return $this->panier;
    }
}
