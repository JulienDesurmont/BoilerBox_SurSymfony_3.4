<?php
namespace Lci\BoilerBoxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Configuration
 *
 * @ORM\Entity
 * @ORM\Table(name="configuration")
 * @ORM\Entity(repositoryClass="Lci\BoilerBoxBundle\Entity\ConfigurationRepository")
*/
class Configuration {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
    */
	protected $id;

	/**
     * @var string
     * 
     * @ORM\Column(type="string", length=255)
    */
	protected $parametre;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
    */
    protected $valeur;


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
     * Set parametre
     *
     * @param string $parametre
     * @return Configuration
     */
    public function setParametre($parametre)
    {
        $this->parametre = $parametre;

        return $this;
    }

    /**
     * Get parametre
     *
     * @return string 
     */
    public function getParametre()
    {
        return $this->parametre;
    }

    /**
     * Set valeur
     *
     * @param string $valeur
     * @return Configuration
     */
    public function setValeur($valeur)
    {
        $this->valeur = $valeur;

        return $this;
    }

    /**
     * Get valeur
     *
     * @return string 
     */
    public function getValeur()
    {
        return $this->valeur;
    }
}
