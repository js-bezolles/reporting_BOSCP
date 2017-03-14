<?php
// src/AppBundle/Entity/Partenaire.php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PartenaireRepository")
 * @ORM\Table(name="partenaire")
 */
class Partenaire
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
     * @ORM\ManyToMany(targetEntity="Moderateur", mappedBy="partenaires", fetch="EAGER")
     */
    private $moderateurs;

    /**
     * @ORM\ManyToMany(targetEntity="UserBundle\Entity\User", mappedBy="partenaires", fetch="EAGER")
     */
    private $users;

    public function __construct() {
        $this->moderateurs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString() {
        return $this->nom != null ? $this->nom : "";
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
     * @return Partenaire
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
     * Add moderateurs
     *
     * @param \AppBundle\Entity\Moderateur $moderateurs
     * @return Partenaire
     */
    public function addModerateur(\AppBundle\Entity\Moderateur $moderateurs)
    {
        $this->moderateurs[] = $moderateurs;

        return $this;
    }

    /**
     * Remove moderateurs
     *
     * @param \AppBundle\Entity\Moderateur $moderateurs
     */
    public function removeModerateur(\AppBundle\Entity\Moderateur $moderateurs)
    {
        $this->moderateurs->removeElement($moderateurs);
    }

    /**
     * Get moderateurs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getModerateurs()
    {
        return $this->moderateurs;
    }

    /**
     * Add users
     *
     * @param \UserBundle\Entity\User $users
     * @return Partenaire
     */
    public function addUser(\UserBundle\Entity\User $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \UserBundle\Entity\User $users
     */
    public function removeUser(\UserBundle\Entity\User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }
}
