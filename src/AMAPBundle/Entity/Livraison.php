<?php

namespace AMAPBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Livraison
 *
 * @ORM\Table(name="livraison")
 * @ORM\Entity(repositoryClass="AMAPBundle\Repository\LivraisonRepository")
 */
class Livraison
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
     * @var \DateTime
     *
     * @ORM\Column(name="dateLivraison", type="datetime")
     */
    private $dateLivraison;

    /**
     * @var bool
     *
     * @ORM\Column(name="estLivree", type="boolean")
     */
    private $estLivree;


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
     * Set dateLivraison
     *
     * @param \DateTime $dateLivraison
     *
     * @return Livraison
     */
    public function setDateLivraison($dateLivraison)
    {
        $this->dateLivraison = $dateLivraison;

        return $this;
    }

    /**
     * Get dateLivraison
     *
     * @return \DateTime
     */
    public function getDateLivraison()
    {
        return $this->dateLivraison;
    }

    /**
     * Set estLivree
     *
     * @param boolean $estLivree
     *
     * @return Livraison
     */
    public function setEstLivree($estLivree)
    {
        $this->estLivree = $estLivree;

        return $this;
    }

    /**
     * Get estLivree
     *
     * @return bool
     */
    public function getEstLivree()
    {
        return $this->estLivree;
    }
}
