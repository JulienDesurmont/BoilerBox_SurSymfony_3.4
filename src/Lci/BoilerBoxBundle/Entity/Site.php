<?php
//src/Lci/BoilerBoxBundle/Entity/Site.php

namespace Lci\BoilerBoxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Site
 * @ORM\Entity
 * @UniqueEntity("affaire")
 * @ORM\Table(name="site")
 * @ORM\Entity(repositoryClass="Lci\BoilerBoxBundle\Entity\SiteRepository")
 * @ORM\HasLifecycleCallbacks
*/
class Site
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string",length=255)
     */ 
    protected $url;

    /**
     * @var string
     *
     * @ORM\Column(type="string",length=255)
    */
    protected $intitule;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
    */
    protected $typeSite;


    /**
     * @var string
     *
     * @ORM\Column(type="string",length=20, unique=true)
    */
    protected $affaire;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", name="disponibilite", options={"default":2})
    */
    protected $disponibilite;


    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", options={"default":1})
    */
    protected $configBoilerBox;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", options={"default":1})
    */
    protected $accesDistant;


    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", options={"default":1})
	*/
	protected $connexion3g;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", options={"default":0})
    */
    protected $connexionAdsl;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
    */
    protected $connexion;


    /** 
     * @var datetime
     *
     * @ORM\Column(type="datetime", name="dateAccess", nullable=true)
    */
    protected $dateAccess;

    /**
     * @var datetime
     *
     * @ORM\Column(type="datetime", name="dateAccessSucceded", nullable=true)
    */
    protected $dateAccessSucceded;


    /**
     * One Site can have many Modules
     * @ORM\OneToMany(targetEntity="Module", mappedBy="site")
    */
    protected $module;


   
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->module = new \Doctrine\Common\Collections\ArrayCollection();
		$this->accesDistant = false;
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
     * Set url
     *
     * @param string $url
     * @return Site
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set intitule
     *
     * @param string $intitule
     * @return Site
     */
    public function setIntitule($intitule)
    {
        $this->intitule = $intitule;

        return $this;
    }

    /**
     * Get intitule
     *
     * @return string 
     */
    public function getIntitule()
    {
        return $this->intitule;
    }

    /**
     * Set affaire
     *
     * @param string $affaire
     * @return Site
     */
    public function setAffaire($affaire)
    {
        $this->affaire = strtoupper($affaire);

        return $this;
    }

    /**
     * Get affaire
     *
     * @return string 
     */
    public function getAffaire()
    {
        return $this->affaire;
    }


    /**
     * Set disponibilite
     *
     * @param integer $disponibilite
     * @return Site
     */
    public function setDisponibilite($disponibilite)
    {
        $this->disponibilite = $disponibilite;

        return $this;
    }

    /**
     * Get disponibilite
     *
     * @return integer 
     */
    public function getDisponibilite()
    {
        return $this->disponibilite;
    }


    /**
     * Set dateAccess
     *
     * @param \DateTime $dateAccess
     * @return Site
     */
    public function setDateAccess($dateAccess)
    {
        $this->dateAccess = $dateAccess;

        return $this;
    }

    /**
     * Get dateAccess
     *
     * @return \DateTime 
     */
    public function getDateAccess()
    {
        return $this->dateAccess;
    }



    /**
     * Set dateAccessSucceded
     *
     * @param \DateTime $dateAccessSucceded
     * @return Site
     */
    public function setDateAccessSucceded($dateAccessSucceded)
    {
        $this->dateAccessSucceded = $dateAccessSucceded;

        return $this;
    }

    /**
     * Get dateAccessSucceded
     *
     * @return \DateTime
     */
    public function getDateAccessSucceded()
    {
        return $this->dateAccessSucceded;
    }


    /**
     * Add module
     *
     * @param \Lci\BoilerBoxBundle\Entity\Module $module
     * @return Site
     */
    public function addModule(\Lci\BoilerBoxBundle\Entity\Module $module)
    {
        $this->module[] = $module;

        return $this;
    }

    /**
     * Remove module
     *
     * @param \Lci\BoilerBoxBundle\Entity\Module $module
     */
    public function removeModule(\Lci\BoilerBoxBundle\Entity\Module $module)
    {
        $this->module->removeElement($module);
    }

    /**
     * Get module
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * Set configBoilerBox
     *
     * @param boolean $configBoilerBox
     * @return Site
     */
    public function setConfigBoilerBox($configBoilerBox)
    {
        $this->configBoilerBox = $configBoilerBox;

        return $this;
    }

    /**
     * Get configBoilerBox
     *
     * @return boolean 
     */
    public function getConfigBoilerBox()
    {
        return $this->configBoilerBox;
    }

    /**
     * Set connexion3g
     *
     * @param boolean $connexion3g
     * @return Site
     */
    public function setConnexion3g($connexion3g)
    {
        $this->connexion3g = $connexion3g;

        return $this;
    }

    /**
     * Get connexion3g
     *
     * @return boolean 
     */
    public function getConnexion3g()
    {
        return $this->connexion3g;
    }

    /**
     * Set connexionAdsl
     *
     * @param boolean $connexionAdsl
     * @return Site
     */
    public function setConnexionAdsl($connexionAdsl)
    {
        $this->connexionAdsl = $connexionAdsl;
		if ($connexionAdsl == true) {
			$this->setConnexion('adsl');
		} else {
			$this->setConnexion('3g');
		}

        return $this;
    }

    /**
     * Get connexionAdsl
     *
     * @return boolean 
     */
    public function getConnexionAdsl()
    {
        return $this->connexionAdsl;
    }

    /**
     * Set accesDistant
     *
     * @param boolean $accesDistant
     * @return Site
     */
    public function setAccesDistant($accesDistant)
    {
        $this->accesDistant = $accesDistant;

        return $this;
    }

    /**
     * Get accesDistant
     *
     * @return boolean 
     */
    public function getAccesDistant()
    {
        return $this->accesDistant;
    }

    /**
     * Set connexion
     *
     * @param string $connexion
     * @return Site
     */
    public function setConnexion($connexion)
    {
        $this->connexion = $connexion;

        return $this;
    }

    /**
     * Get connexion
     *
     * @return string 
     */
    public function getConnexion()
    {
        return $this->connexion;
    }

    /**
     * Set typeSite
     *
     * @param string $typeSite
     * @return Site
     */
    public function setTypeSite($typeSite)
    {
        $this->typeSite = $typeSite;

        return $this;
    }

    /**
     * Get typeSite
     *
     * @return string 
     */
    public function getTypeSite()
    {
        return $this->typeSite;
    }
}
