<?php

namespace AMAPBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
/**
 * Acteur
 *
 * @ORM\Table(name="acteur")
 * @ORM\Entity(repositoryClass="AMAPBundle\Repository\ActeurRepository")
 */
class Acteur extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=true)
     */
    private $prenom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_naissance", type="date", nullable=true)
     */
    private $dateNaissance;

    /**
    * @ORM\ManyToOne(targetEntity="AMAPBundle\Entity\TypeActeur")
    */
    private $typeActeur;

    /**
    * @ORM\OneToOne(targetEntity="AMAPBundle\Entity\Adresse", cascade={"persist"})
    */
    private $adresse;

	/**
    * @ORM\ManyToOne(targetEntity="AMAPBundle\Entity\Amap")
    */
    private $amap;

    public function __construct()
    {
        parent::__construct();
        // your own logic
        
    }
    
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
     * Set nom
     *
     * @param string $nom
     *
     * @return Acteur
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Acteur
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set dateNaissance
     *
     * @param \DateTime $dateNaissance
     *
     * @return Acteur
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    /**
     * Get dateNaissance
     *
     * @return \DateTime
     */
    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

    /**
     * Set typeActeur
     *
     * @param \AMAPBundle\Entity\TypeActeur $typeActeur
     *
     * @return Acteur
     */
    public function setTypeActeur(\AMAPBundle\Entity\TypeActeur $typeActeur = null)
    {
        $this->typeActeur = $typeActeur;

        return $this;
    }

    /**
     * Get typeActeur
     *
     * @return \AMAPBundle\Entity\TypeActeur
     */
    public function getTypeActeur()
    {
        return $this->typeActeur;
    }

    /**
     * Set adresse
     *
     * @param \AMAPBundle\Entity\Adresse $adresse
     *
     * @return Acteur
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
     * Set amap
     *
     * @param \AMAPBundle\Entity\Amap $amap
     *
     * @return Acteur
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
}
