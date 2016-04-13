<?php

namespace AMAPBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Panier
 *
 * @ORM\Table(name="panier")
 * @ORM\Entity(repositoryClass="AMAPBundle\Repository\PanierRepository")
 */
class Panier
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
     * @ORM\Column(name="libelle", type="string", length=255)
     */
    private $libelle;

    /**
    * @ORM\OneToMany(targetEntity="AMAPBundle\Entity\PanierProduit", mappedBy="panier")
    */
    protected $panierproduit;

    /**
    * @ORM\ManyToOne(targetEntity="AMAPBundle\Entity\Saison")
    */
    protected $saison;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="prix", type="integer")
     */
    protected $prix;


    /**
    * @ORM\ManyToOne(targetEntity="AMAPBundle\Entity\TypePanier")
    */
    protected $typePanier;
    
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
     * @return Panier
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
     * @return Panier
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
     * Set saison
     *
     * @param \AMAPBundle\Entity\Saison $saison
     *
     * @return Panier
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

    /**
     * Set typePanier
     *
     * @param \AMAPBundle\Entity\TypePanier $typePanier
     *
     * @return Panier
     */
    public function setTypePanier(\AMAPBundle\Entity\TypePanier $typePanier = null)
    {
        $this->typePanier = $typePanier;

        return $this;
    }

    /**
     * Get typePanier
     *
     * @return \AMAPBundle\Entity\TypePanier
     */
    public function getTypePanier()
    {
        return $this->typePanier;
    }

    /**
     * Set prix
     *
     * @param integer $prix
     *
     * @return Panier
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return integer
     */
    public function getPrix()
    {
        return $this->prix;
    }
}
