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
}
