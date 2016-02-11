<?php

namespace AMAPBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Produit
 *
 * @ORM\Table(name="produit")
 * @ORM\Entity(repositoryClass="AMAPBundle\Repository\ProduitRepository")
 */
class Produit
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
    * @ORM\OneToMany(targetEntity="AMAPBundle\Entity\PanierProduit", mappedBy="produit")
    */
    protected $panierproduit;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=255)
     */
    private $libelle;
  
    /**
    * @ORM\ManyToOne(targetEntity="AMAPBundle\Entity\Famille")
    */
    protected $famille;
    
    
  
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->panierproduit = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set libelle
     *
     * @param string $libelle
     *
     * @return Produit
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Add panierproduit
     *
     * @param \AMAPBundle\Entity\PanierProduit $panierproduit
     *
     * @return Produit
     */
    public function addPanierproduit(\AMAPBundle\Entity\PanierProduit $panierproduit)
    {
        $this->panierproduit[] = $panierproduit;

        return $this;
    }

    /**
     * Remove panierproduit
     *
     * @param \AMAPBundle\Entity\PanierProduit $panierproduit
     */
    public function removePanierproduit(\AMAPBundle\Entity\PanierProduit $panierproduit)
    {
        $this->panierproduit->removeElement($panierproduit);
    }

    /**
     * Get panierproduit
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPanierproduit()
    {
        return $this->panierproduit;
    }

    /**
     * Set famille
     *
     * @param \AMAPBundle\Entity\Famille $famille
     *
     * @return Produit
     */
    public function setFamille(\AMAPBundle\Entity\Famille $famille = null)
    {
        $this->famille = $famille;

        return $this;
    }

    /**
     * Get famille
     *
     * @return \AMAPBundle\Entity\Famille
     */
    public function getFamille()
    {
        return $this->famille;
    }

    /**
     * Set saison
     *
     * @param \AMAPBundle\Entity\Saison $saison
     *
     * @return Produit
     */
    public function setSaison(\AMAPBundle\Entity\Saison $saison = null)
    {
        $this->saison = $saison;

        return $this;
    }

    /**
     * Get saison
     *
     * @return \AMAPBundle\Entity\Saison
     */
    public function getSaison()
    {
        return $this->saison;
    }
}
