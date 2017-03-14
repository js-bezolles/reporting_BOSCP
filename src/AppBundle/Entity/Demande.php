<?php
// src/AppBundle/Entity/demande.php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DemandeRepository")
 * @ORM\Table(name="demande")
 */
class Demande
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
    * @ORM\Column(type="integer",nullable=true)
     */
    private $num_demande;

    /**
     * @ORM\Column(type="string", length=100,nullable=true)
     */
    private $cod_etat_demande;

    /**
     * @ORM\Column(type="string", length=100,nullable=true)
     */
    private $code_origine;

    /**
     * @ORM\Column(type="string", length=100,nullable=true)
     */
    private $ind_confirm_pro;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $num_epj;

    /** @ORM\Column(type="string", length=100,nullable=true)*/
    private $nat_pro;

    /** @ORM\Column(type="datetime",nullable=true) */
    private $date_trt;

    /** @ORM\Column(type="datetime",nullable=true) */
    private $date_reception;

    /**
     * @ORM\Column(type="string", length=100,nullable=true)
     */
    private $moderateur;

    /**
     * @ORM\Column(type="string", length=100,nullable=true)
     */
    private $im_label_dd;


    /**
     * Set id
     *
     * @param integer $id
     * @return Demande
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * Set num_demande
     *
     * @param integer $numDemande
     * @return Demande
     */
    public function setNumDemande($numDemande)
    {
        $this->num_demande = $numDemande;

        return $this;
    }

    /**
     * Get num_demande
     *
     * @return integer 
     */
    public function getNumDemande()
    {
        return $this->num_demande;
    }

    /**
     * Set cod_etat_demande
     *
     * @param string $codEtatDemande
     * @return Demande
     */
    public function setCodEtatDemande($codEtatDemande)
    {
        $this->cod_etat_demande = $codEtatDemande;

        return $this;
    }

    /**
     * Get cod_etat_demande
     *
     * @return string 
     */
    public function getCodEtatDemande()
    {
        return $this->cod_etat_demande;
    }

    /**
     * Set code_origine
     *
     * @param string $codeOrigine
     * @return Demande
     */
    public function setCodeOrigine($codeOrigine)
    {
        $this->code_origine = $codeOrigine;

        return $this;
    }

    /**
     * Get code_origine
     *
     * @return string 
     */
    public function getCodeOrigine()
    {
        return $this->code_origine;
    }

    /**
     * Set ind_confirm_pro
     *
     * @param string $indConfirmPro
     * @return Demande
     */
    public function setIndConfirmPro($indConfirmPro)
    {
        $this->ind_confirm_pro = $indConfirmPro;

        return $this;
    }

    /**
     * Get ind_confirm_pro
     *
     * @return string 
     */
    public function getIndConfirmPro()
    {
        return $this->ind_confirm_pro;
    }

    /**
     * Set num_epj
     *
     * @param integer $numEpj
     * @return Demande
     */
    public function setNumEpj($numEpj)
    {
        $this->num_epj = $numEpj;

        return $this;
    }

    /**
     * Get num_epj
     *
     * @return integer 
     */
    public function getNumEpj()
    {
        return $this->num_epj;
    }

    /**
     * Set nat_pro
     *
     * @param string $natPro
     * @return Demande
     */
    public function setNatPro($natPro)
    {
        $this->nat_pro = $natPro;

        return $this;
    }

    /**
     * Get nat_pro
     *
     * @return string 
     */
    public function getNatPro()
    {
        return $this->nat_pro;
    }

    /**
     * Set date_trt
     *
     * @param \DateTime $dateTrt
     * @return Demande
     */
    public function setDateTrt($dateTrt)
    {
        $this->date_trt = $dateTrt;

        return $this;
    }

    /**
     * Get date_trt
     *
     * @return \DateTime 
     */
    public function getDateTrt()
    {
        return $this->date_trt;
    }

    /**
     * Set date_reception
     *
     * @param \DateTime $dateReception
     * @return Demande
     */
    public function setDateReception($dateReception)
    {
        $this->date_reception = $dateReception;

        return $this;
    }

    /**
     * Get date_reception
     *
     * @return \DateTime 
     */
    public function getDateReception()
    {
        return $this->date_reception;
    }

    /**
     * Set moderateur
     *
     * @param string $moderateur
     * @return Demande
     */
    public function setModerateur($moderateur)
    {
        $this->moderateur = $moderateur;

        return $this;
    }

    /**
     * Get moderateur
     *
     * @return string 
     */
    public function getModerateur()
    {
        return $this->moderateur;
    }

    /**
     * Set im_label_dd
     *
     * @param string $imLabelDd
     * @return Demande
     */
    public function setImLabelDd($imLabelDd)
    {
        $this->im_label_dd = $imLabelDd;

        return $this;
    }

    /**
     * Get im_label_dd
     *
     * @return string 
     */
    public function getImLabelDd()
    {
        return $this->im_label_dd;
    }
}
