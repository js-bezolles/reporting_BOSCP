<?php
// src/AppBundle/Entity/Moderateur.php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ModerateurRepository")
 * @ORM\Table(name="moderateur")
 */
class Moderateur
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $nom;

    /**
     * @ORM\ManyToMany(targetEntity="Partenaire", inversedBy="moderateurs", fetch="EAGER")
     * @ORM\JoinTable(name="moderateur_partenaire")
     */
    private $partenaires;

    public function __construct() {
        $this->partenaires = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nom
     *
     * @param string $nom
     * @return Moderateur
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
     * Add partenaires
     *
     * @param \AppBundle\Entity\Partenaire $partenaires
     * @return Moderateur
     */
    public function addPartenaire(\AppBundle\Entity\Partenaire $partenaires)
    {
        $this->partenaires[] = $partenaires;

        return $this;
    }

    /**
     * Remove partenaires
     *
     * @param \AppBundle\Entity\Partenaire $partenaires
     */

    public function removePartenaire(\AppBundle\Entity\Partenaire $partenaires)
    {
    $this->partenaires->removeElement($partenaires);
    }

    /**
     * Get partenaires
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPartenaires()
    {
        return $this->partenaires;
    }

    public function __toString() {
        return $this->nom != null ? $this->nom : "";
    }
}
