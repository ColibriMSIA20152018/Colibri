<?php

namespace AMAPBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PanierProduit
 *
 * @ORM\Table(name="panier_produit")
 * @ORM\Entity(repositoryClass="AMAPBundle\Repository\PanierProduitRepository")
 */
class PanierProduit
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
     * @ORM\Column(name="quantite", type="integer")
     */
    private $quantite;

    /**
    * @ORM\ManyToOne(targetEntity="AMAPBundle\Entity\Produit", inversedBy="panierproduit")
    */
    protected $produit;

    /**
    * @ORM\ManyToOne(targetEntity="AMAPBundle\Entity\Panier", inversedBy="panierproduit")
    */
    protected $panier;
    


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
     * Set quantite
     *
     * @param integer $quantite
     *
     * @return PanierProduit
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get quantite
     *
     * @return integer
     */
    public function getQuantite()
    {
        return $this->quantite;
    }

    /**
     * Set produit
     *
     * @param \AMAPBundle\Entity\Produit $produit
     *
     * @return PanierProduit
     */
    public function setProduit(\AMAPBundle\Entity\Produit $produit = null)
    {
        $this->produit = $produit;

        return $this;
    }

    /**
     * Get produit
     *
     * @return \AMAPBundle\Entity\Produit
     */
    public function getProduit()
    {
        return $this->produit;
    }

    /**
     * Set panier
     *
     * @param \AMAPBundle\Entity\Panier $panier
     *
     * @return PanierProduit
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
