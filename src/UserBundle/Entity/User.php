<?php
// src/UserBundle/Entity/User.php

namespace UserBundle\Entity;

use AppBundle\Entity\Partenaire;
use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Annotations;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Partenaire", inversedBy="users", fetch="EAGER")
     * @ORM\JoinTable(name="users_partenaires")
     */
    private $partenaires;

    public function __construct() {
        parent::__construct();
        $this->partenaires = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add partenaires
     *
     * @param \AppBundle\Entity\Partenaire $partenaires
     * @return User
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
}
