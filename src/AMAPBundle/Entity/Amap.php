<?php

namespace AMAPBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Amap
 *
 * @ORM\Table(name="amap")
 * @ORM\Entity(repositoryClass="AMAPBundle\Repository\AmapRepository")
 */
class Amap
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
    * @ORM\OneToOne(targetEntity="AMAPBundle\Entity\Adresse", cascade={"persist"})
    */
    private $adresse;

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
     * Set libelle
     *
     * @param string $libelle
     *
     * @return Amap
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
     * Set adresse
     *
     * @param \AMAPBundle\Entity\Adresse $adresse
     *
     * @return Amap
     */
    public function setAdresse(\AMAPBundle\Entity\Adresse $adresse = null)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return \AMAPBundle\Entity\Adresse
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set entrepot
     *
     * @param \AMAPBundle\Entity\Entrepot $entrepot
     *
     * @return Amap
     */
    public function setEntrepot(\AMAPBundle\Entity\Entrepot $entrepot = null)
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
