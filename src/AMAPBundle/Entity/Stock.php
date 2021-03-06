<?php

namespace AMAPBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stock
 *
 * @ORM\Table(name="stock")
 * @ORM\Entity(repositoryClass="AMAPBundle\Repository\StockRepository")
 */
class Stock
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
    * @ORM\ManyToOne(targetEntity="AMAPBundle\Entity\Produit")
    */
    protected $produit;

	/**
	* @ORM\ManyToOne(targetEntity="AMAPBundle\Entity\Entrepot", inversedBy="stock")
	* @ORM\JoinColumn(nullable=false)
	*/
	private $entrepot;

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
     * Set quantite
     *
     * @param integer $quantite
     *
     * @return Stock
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get quantite
     *
     * @return int
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
     * @return Stock
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
     * Set entrepot
     *
     * @param \AMAPBundle\Entity\Entrepot $entrepot
     *
     * @return Entrepot
     */
    public function setEntrepot(\AMAPBundle\Entity\Entrepot $entrepot)
    {
        $this->entrepot = $entrepot;

        return $this;
    }

    /**
     * Get entrepot
     *
     * @return \AMAPBundle\Entity\Entrepot
     */
    public function getEntrepot()
    {
        return $this->entrepot;
    }
}
