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
    * @ORM\ManyToOne(targetEntity="AMAPBundle\Entity\Amap")
    */
    private $amap;

    /**
    * @ORM\ManyToOne(targetEntity="AMAPBundle\Entity\Panier")
    */
    private $panier;

	/**
	 * @ORM\ManyToMany(targetEntity="AMAPBundle\Entity\Livraison")
	 */
	private $livraisons;

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

    /**
     * Set amap
     *
     * @param \AMAPBundle\Entity\Amap $amap
     *
     * @return Contrat
     */
    public function setAmap(\AMAPBundle\Entity\Amap $amap = null)
    {
        $this->amap = $amap;

        return $this;
    }

    /**
     * Get amap
     *
     * @return \AMAPBundle\Entity\Amap
     */
    public function getAmap()
    {
        return $this->amap;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->livraisons = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add livraison
     *
     * @param \AMAPBundle\Entity\Livraison $livraison
     *
     * @return Contrat
     */
    public function addLivraison(\AMAPBundle\Entity\Livraison $livraison)
    {
        $this->livraisons[] = $livraison;

        return $this;
    }

    /**
     * Remove livraison
     *
     * @param \AMAPBundle\Entity\Livraison $livraison
     */
    public function removeLivraison(\AMAPBundle\Entity\Livraison $livraison)
    {
        $this->livraisons->removeElement($livraison);
    }

    /**
     * Get livraisons
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLivraisons()
    {
        return $this->livraisons;
    }
}
