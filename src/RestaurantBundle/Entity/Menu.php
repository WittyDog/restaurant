<?php

namespace RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Menu
 *
 * @ORM\Table(name="menu")
 * @ORM\Entity(repositoryClass="RestaurantBundle\Repository\MenuRepository")
 */
class Menu
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
     *
     * @Assert\NotBlank()
     *
     * @ORM\ManyToOne(targetEntity="RestaurantBundle\Entity\Utilisateur", cascade={"persist"})
     */
    private $auteur;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var float
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="prix", type="float")
     */
    private $prix;

    /**
     * @var int
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="ordre", type="integer")
     */
    private $ordre;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="statut", type="string", length=255)
     */
    private $statut;

    /**
     * @ORM\ManyToMany(targetEntity="RestaurantBundle\Entity\Plat", cascade={"persist"})
     */
    private $plats;

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
     * Set titre
     *
     * @param string $titre
     *
     * @return Menu
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set prix
     *
     * @param float $prix
     *
     * @return Menu
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return float
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set ordre
     *
     * @param integer $ordre
     *
     * @return Menu
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;

        return $this;
    }

    /**
     * Get ordre
     *
     * @return int
     */
    public function getOrdre()
    {
        return $this->ordre;
    }

    /**
     * Set statut
     *
     * @param string $statut
     *
     * @return Menu
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return string
     */
    public function getStatut()
    {
        return $this->statut;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->plats = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set auteur
     *
     * @param \RestaurantBundle\Entity\Utilisateur $auteur
     *
     * @return Menu
     */
    public function setAuteur(\RestaurantBundle\Entity\Utilisateur $auteur = null)
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * Get auteur
     *
     * @return \RestaurantBundle\Entity\Utilisateur
     */
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
     * Add plat
     *
     * @param \RestaurantBundle\Entity\Plat $plat
     *
     * @return Menu
     */
    public function addPlat(\RestaurantBundle\Entity\Plat $plat)
    {
        $this->plats[] = $plat;

        return $this;
    }

    /**
     * Remove plat
     *
     * @param \RestaurantBundle\Entity\Plat $plat
     */
    public function removePlat(\RestaurantBundle\Entity\Plat $plat)
    {
        $this->plats->removeElement($plat);
    }

    /**
     * Get plats
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlats()
    {
        return $this->plats;
    }
}
