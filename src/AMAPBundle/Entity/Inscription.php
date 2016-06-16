<?php

namespace AMAPBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Inscription
 *
 * @ORM\Table(name="inscription")
 * @ORM\Entity(repositoryClass="AMAPBundle\Repository\InscriptionRepository")
 */
class Inscription
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
    private $acteur;
    
    /**
    * @ORM\ManyToOne(targetEntity="AMAPBundle\Entity\Panier")
    */
    private $panier;
    
    /**
    * @ORM\ManyToOne(targetEntity="AMAPBundle\Entity\amap")
    */
    private $amap;
    
    

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
     * Set acteur
     *
     * @param \AMAPBundle\Entity\Acteur $acteur
     *
     * @return Inscription
     */
    public function setActeur(\AMAPBundle\Entity\Acteur $acteur = null)
    {
        $this->acteur = $acteur;

        return $this;
    }

    /**
     * Get acteur
     *
     * @return \AMAPBundle\Entity\Acteur
     */
    public function getActeur()
    {
        return $this->acteur;
    }

    /**
     * Set panier
     *
     * @param \AMAPBundle\Entity\Panier $panier
     *
     * @return Inscription
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
     * @param \AMAPBundle\Entity\amap $amap
     *
     * @return Inscription
     */
    public function setAmap(\AMAPBundle\Entity\amap $amap = null)
    {
        $this->amap = $amap;

        return $this;
    }

    /**
     * Get amap
     *
     * @return \AMAPBundle\Entity\amap
     */
    public function getAmap()
    {
        return $this->amap;
    }
}
