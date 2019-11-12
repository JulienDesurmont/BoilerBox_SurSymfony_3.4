<?php
// Lci/BoilerBoxBundle/Entity/User.php
namespace Lci\BoilerBoxBundle\Entity;

//use FOS\UserBundle\Model\User as BaseUser;
use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * User
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Lci\BoilerBoxBundle\Entity\UserRepository")
 * @UniqueEntity("username")
 * @ORM\HasLifecycleCallbacks
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
	* @var string
	* 
 	* @ORM\Column(name="totpKey", type="string", length=16)
	*
	*/
	protected $totpKey;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=255)
     *
     * @Assert\NotBlank()
    */
    protected $label;

    /**
     * @ORM\ManyToMany(targetEntity="Lci\BoilerBoxBundle\Entity\Site", cascade={"persist"})
     * @ORM\OrderBy({"affaire" = "ASC"})
    */
    protected $site;

    /**
     * One User can have many problems to solve
     * @ORM\OneToMany(targetEntity="ProblemeTechnique", mappedBy="user", cascade={"remove"})
    */
    protected $problemeTechnique;


    /**
     * Un utilisateur peut être la cible de plusieurs bons d'attachements
     *
     * @ORM\OneToMany(targetEntity="BonsAttachement", mappedBy="user", cascade={"remove"})
    */
    protected $bonsAttachement;

    /**
     * Un utilisateur peut être la cible de plusieurs bons d'attachements
     *
     * @ORM\OneToMany(targetEntity="BonsAttachement", mappedBy="userInitiateur", cascade={"remove"})
    */
    protected $bonsAttachementInitiateur;



	/**
	 * Un utilisateur peut valider plusieurs bons
	 *
	 * @ORM\OneToMany(targetEntity="Validation", mappedBy="user")
	*/
	protected $validations;



    public function __construct()
    {
       parent::__construct();
       if (empty($this->roles)) {
         $this->roles[] = 'ROLE_USER';
       }
		$this->enabled = true;
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
     * Set totpKey
     *
     * @param string $totpKey
     * @return User
     */
    public function setTotpKey($key)
    {
        $this->totpKey = $key;

        return $this;
    }

    /**
     * Get totpKey
     *
     * @return string
     */
    public function gettotpKey()
    {
        return $this->totpKey;
    }




    /**
     * Add site
     *
     * @param \Lci\BoilerBoxBundle\Entity\Site $site
     * @return User
     */
    public function addSite(\Lci\BoilerBoxBundle\Entity\Site $site)
    {
        $this->site[] = $site;

        return $this;
    }

    /**
     * Remove site
     *
     * @param \Lci\BoilerBoxBundle\Entity\Site $site
     */
    public function removeSite(\Lci\BoilerBoxBundle\Entity\Site $site)
    {
        $this->site->removeElement($site);
    }

    /**
     * Get site
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * Set label
     *
     * @param string $label
     * @return User
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string 
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Add problemeTechnique
     *
     * @param \Lci\BoilerBoxBundle\Entity\ProblemeTechnique $problemeTechnique
     * @return User
     */
    public function addProblemeTechnique(\Lci\BoilerBoxBundle\Entity\ProblemeTechnique $problemeTechnique)
    {
        $this->problemeTechnique[] = $problemeTechnique;

        return $this;
    }

    /**
     * Remove problemeTechnique
     *
     * @param \Lci\BoilerBoxBundle\Entity\ProblemeTechnique $problemeTechnique
     */
    public function removeProblemeTechnique(\Lci\BoilerBoxBundle\Entity\ProblemeTechnique $problemeTechnique)
    {
        $this->problemeTechnique->removeElement($problemeTechnique);
    }

    /**
     * Get problemeTechnique
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProblemeTechnique()
    {
        return $this->problemeTechnique;
    }


    public function setUsername($username)
    {
        $this->username = $username;
		$this->label = $username;
        return $this;
    }


    /**
     * Add bonsAttachement
     *
     * @param \Lci\BoilerBoxBundle\Entity\BonsAttachement $bonsAttachement
     * @return User
     */
    public function addBonsAttachement(\Lci\BoilerBoxBundle\Entity\BonsAttachement $bonsAttachement)
    {
        $this->bonsAttachement[] = $bonsAttachement;

        return $this;
    }

    /**
     * Remove bonsAttachement
     *
     * @param \Lci\BoilerBoxBundle\Entity\BonsAttachement $bonsAttachement
     */
    public function removeBonsAttachement(\Lci\BoilerBoxBundle\Entity\BonsAttachement $bonsAttachement)
    {
        $this->bonsAttachement->removeElement($bonsAttachement);
    }

    /**
     * Get bonsAttachement
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBonsAttachement()
    {
        return $this->bonsAttachement;
    }

    /**
     * Add validations
     *
     * @param \Lci\BoilerBoxBundle\Entity\Validation $validations
     * @return User
     */
    public function addValidation(\Lci\BoilerBoxBundle\Entity\Validation $validations)
    {
        $this->validations[] = $validations;

        return $this;
    }

    /**
     * Remove validations
     *
     * @param \Lci\BoilerBoxBundle\Entity\Validation $validations
     */
    public function removeValidation(\Lci\BoilerBoxBundle\Entity\Validation $validations)
    {
        $this->validations->removeElement($validations);
    }

    /**
     * Get validations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getValidations()
    {
        return $this->validations;
    }

    /**
     * Add bonsAttachementInitiateur
     *
     * @param \Lci\BoilerBoxBundle\Entity\BonsAttachement $bonsAttachementInitiateur
     * @return User
     */
    public function addBonsAttachementInitiateur(\Lci\BoilerBoxBundle\Entity\BonsAttachement $bonsAttachementInitiateur)
    {
        $this->bonsAttachementInitiateur[] = $bonsAttachementInitiateur;

        return $this;
    }

    /**
     * Remove bonsAttachementInitiateur
     *
     * @param \Lci\BoilerBoxBundle\Entity\BonsAttachement $bonsAttachementInitiateur
     */
    public function removeBonsAttachementInitiateur(\Lci\BoilerBoxBundle\Entity\BonsAttachement $bonsAttachementInitiateur)
    {
        $this->bonsAttachementInitiateur->removeElement($bonsAttachementInitiateur);
    }

    /**
     * Get bonsAttachementInitiateur
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBonsAttachementInitiateur()
    {
        return $this->bonsAttachementInitiateur;
    }

	public function myGetRoles() {
		$str_roles = "";
		$pattern_role = '#ROLE_(.*)#';
		$replacement = '$1';
		foreach($this->getRoles() as $role) {
			$nouveau_role = strtolower(preg_replace($pattern_role, $replacement, $role));
			if ($nouveau_role != 'user') {
				$str_roles .= strtolower(preg_replace($pattern_role, $replacement, $role)).', ';	
			}
		}
		if (! empty($str_roles) ) {
			return substr(trim($str_roles),0,-1);
		} else {
			return 'client';
		}
	}

	public function myGetRolesHtml() {
		$str_roles = $this->myGetRoles();
		$pattern_admin = '#admin#';
		$pattern_responsable_parc = '#responsable_parc#';
		$pattern_gestion_ba = '#gestion_ba#';
        $pattern_saisie_ba = '#saisie_ba#';
		$pattern_technicien = '#technicien#';
        $pattern_auto_enquete = '#auto_enquete#';
		$pattern_client = '#client#';

		$pattern_services = '#service#';
		$pattern_secreteriat = '#secreteriat#';

		if (preg_match($pattern_auto_enquete, $str_roles)) {
			return "<span class='auto_enquete'>".$str_roles."</span>";
        } elseif (preg_match($pattern_admin, $str_roles)) {
			return "<span class='administrateur'>".$str_roles."</span>";
		} elseif (preg_match($pattern_responsable_parc, $str_roles)) {
            return "<span class='responsable_de_parc'>".$str_roles."</span>";
		} elseif (preg_match($pattern_saisie_ba, $str_roles)) {
            return "<span class='saisie_ba'>".$str_roles."</span>";
        } elseif (preg_match($pattern_services, $str_roles)) {
            return "<span class='services'>".$str_roles."</span>";
        }elseif (preg_match($pattern_secreteriat, $str_roles)) {
            return "<span class='secreteriat'>".$str_roles."</span>";
        }elseif (preg_match($pattern_gestion_ba, $str_roles)) {
            return "<span class='gestion_ba'>".$str_roles."</span>";
        } elseif (preg_match($pattern_technicien, $str_roles)) {
            return "<span class='technicien'>".$str_roles."</span>";
        } else {
        	if (preg_match($pattern_client, $str_roles)) {
        	    return "<span class='client'>".$str_roles."</span>";
        	} else {
				return "<span style='color:black'>".$str_roles."</span>";
			}
		}
	}
}
