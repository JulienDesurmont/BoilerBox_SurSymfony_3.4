<?php
//src/Lci/BoilerBoxBundle/Services/ServiceUtilitaires
//Service effectuant le transfert des fichiers ftp des localisations du site courant
namespace Lci\BoilerBoxBundle\Services;

use Lci\BoilerBoxBundle\Entity\configuration;

class ServiceUtilitaires {

	private $delais_netcat;
	private $label;
	private $em;
	private $dnsServer;
	private $service_configuration;

	public function __construct($doctrine, $service_configuration) {
		## Variable indiquant le delais d'execution (en seconde) de chaque test. Diminuer ce chiffre accelère le traitement des tests multiples
		$this->delais_netcat = 2;
		$this->em = $doctrine->getManager();
	    $this->dnsServer = '8.8.8.8';
		$this->service_configuration = $service_configuration;
	}

	// Fonction créée également dans un Controller - UtilsController.php : function validChoiceAction() - A modifier aussi en cas de modification de cette fonction
	// Utilitaire NETCAT : Analyse la liste des sites avec un indicateur indiquant la réussite ou l'échec du test.
	// N'analyse que les sites accessibles ( paramètre configBoilerBox = true)
	public function analyseAccess() {
		// Mise à jour de la valeur du paramètre : date du dernier test de disponibilité des sites.
		$date_du_jour = new \Datetime();
		$entity_configuration = $this->service_configuration->getEntiteDeConfiguration('date_test_de_disponibilite', $date_du_jour->format('d-m-Y H:i:s'));
		$entity_configuration->setValeur($date_du_jour->format('d-m-Y H:i:s'));
		$this->em->persist($entity_configuration);
		$this->em->flush();

		// Recherche de la liste des sites enregistrés en base de donnée
	    $tab_entities_site = $this->em->getRepository('LciBoilerBoxBundle:Site')->findAll();	
		// Pour chaque site : Récupération de l'adresse ip et test de l'accessibilité sur cette adresse
		$tab_sites = array();
		foreach ($tab_entities_site as $entity_site) {
				// Le test du ping (netcat) n'est effectué que pour les sites accessibles (paramètres accesDistant = true && configBoilerbox ) true)
				if ( ($entity_site->getAccesDistant() == true) && ($entity_site->getConfigBoilerBox() == true) ) {
					$dateAccess = new \Datetime();
					$entity_site->setDateAccess($dateAccess);

					$tab_param_url = $this->recuperationSiteUrl($entity_site->getUrl());
					$commande_adresse_ip_site = "host -t A ".$tab_param_url['url']." ".$this->dnsServer." | grep 'has address' | awk -F' ' '{print $4}'";
	            	$adresse_ip_site = exec($commande_adresse_ip_site, $tab_adresse_ip, $retour_commande);
		        	if ($adresse_ip_site != '') {
						$commande_netcat = "nc -v -n -z -w $this->delais_netcat $adresse_ip_site ".$tab_param_url['port'];
		    		    $last_command_lign = exec($commande_netcat, $tab_netcat, $retour_commande_netcat);
	 	    		    // Interprétation de la réponse et modification de l'entité site passée en paramètre
						$this->interpretation_netcat($entity_site, $retour_commande_netcat);
		        	} else { 
	            	    // Inscription de la date du test dans l'entité site
	            	    $this->em->flush();
					}
				}
		}
		// Retour de la liste des entités [ site ]
		return(0);
	}

	// Fonction qui prend en argument une url de type http://c671.boiler-box.fr/ (ou http://c714.boiler-box.fr:81/) et retourne l'url c671.boiler-box.fr (ou c714.boiler-box.fr)
	private function recuperationSiteUrl($url) {
	    $tab_param_url  = array();
	    $pattern_url = '/^http:\/\/(.+?):?(\d*?)\/$/';
	    if (preg_match($pattern_url, $url, $tab_url)) {
	        if ($tab_url[2] == null) {
	            $tab_url[2] = 80;
	        }
	        $tab_param_url['url']  = $tab_url[1];
	        $tab_param_url['port'] = $tab_url[2];
	    } else {
	        $tab_param_url['url']  = $url;
	        $tab_param_url['port'] = 80;
	    }
	    return($tab_param_url);
	}


	// Définie la date de tentative de connexion - la disponibilité du site et de son site Live en fonction du retour de la commande Netcat - La date de réussite de la commande Netcat
	private function interpretation_netcat($entitySite, $retour_commande_netcat) {
	    // Inscription de la date du test dans l'entité site
	    $site_Live = $entitySite->getAffaire().'L';
	    $entityLive = $this->em->getRepository('LciBoilerBoxBundle:Site')->findOneByAffaire($site_Live);
	    if ($retour_commande_netcat == 0) {
			// Si le ping est ok : On met le paramètre Disponibilité à 0, et on définit le site live comme également disponible
	        $entitySite->setDisponibilite(0);
			$entitySite->setDateAccessSucceded($dateAccess);
	        if ($entityLive != null) { 
				$entityLive->setDisponibilite(0); 
				$entityLive->setDateAccessSucceded($dateAccess);
			}
	    } else {
			// Si le ping n'est pas ok : On met le paramètre Disponibilité à 2
			$entitySite->setDisponibilite(2);
	        if($entityLive != null) { $entityLive->setDisponibilite(2); }
	    }
		$this->em->flush();
	    return(0);
	}
}
