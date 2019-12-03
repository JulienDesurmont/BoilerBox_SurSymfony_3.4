<?php
namespace Lci\BoilerBoxBundle\Controller;

use Lci\BoilerBoxBundle\Entity\Validation;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\JsonResponse;


class AjaxBonsController extends Controller {

// Fonction qui modifie le paramètre EnqueteNecessaire d'un bon
public function setEnqueteAction() {
	$idBon = $_POST['identifiant'];
	$entity_bon = $this->getDoctrine()->getManager()->getRepository('LciBoilerBoxBundle:BonsAttachement')->find($idBon);
	if ($entity_bon->getEnqueteNecessaire() == true) {
		$entity_bon->setEnqueteNecessaire(false);
	} else {
		$entity_bon->setEnqueteNecessaire(true);
	}
	$this->getDoctrine()->getManager()->flush();
	return new Response();
}

// Fonction qui effectue la validation ou la suppression d'une ancienne validation d'une catégorie d'un bon
public function setValidationAction() {
	$em = $this->getDoctrine()->getManager();

    $idBon = $_POST['identifiant'];
    $type = $_POST['type'];
    $sens = $_POST['sens'];
	

	$entity_bon = $em->getRepository('LciBoilerBoxBundle:BonsAttachement')->find($idBon);
	$user = $this->get('security.token_storage')->getToken()->getUser();

	// Si le sens est false c'est que la checkbox est décochée : On définit le champs valide à 0 pour signifier que l'entité Validation est une Dé-Validation
	// Ajout du 02/12/2019 : Lors d'une dévalidation on informe l'ensemble des valideurs par mail.
	if ($sens == 'false') {
		switch ($type) {
			case 'technique':
				$entity_validation = $this->devalidation($entity_bon->getValidationTechnique(), $user);
				$entities_users_validation = $em->getRepository('LciBoilerBoxBundle:User')->myfindByRole('ROLE_SERVICE_TECHNIQUE');
				break;
        	case 'sav':
				$entity_validation = $this->devalidation($entity_bon->getValidationSAV(), $user);
				$entities_users_validation = $em->getRepository('LciBoilerBoxBundle:User')->myfindByRole('ROLE_SERVICE_SAV');
            	break;
        	case 'horaire':
				$entity_validation = $this->devalidation($entity_bon->getValidationHoraire(), $user);
				$entities_users_validation = $em->getRepository('LciBoilerBoxBundle:User')->myfindByRole('ROLE_SERVICE_HORAIRE');
        	    break;
        	case 'facturation':
				$entity_validation = $this->devalidation($entity_bon->getValidationFacturation(), $user);
				$entities_users_validation = $em->getRepository('LciBoilerBoxBundle:User')->myfindByRole('ROLE_SERVICE_FACTURATION');
        	    break;
        	default:
        	    break;
		}
		// Envoi du mail à l'ensemble du groupe de dévalidation
		$this->sendMailDevalidation($type, $entity_bon, $entities_users_validation, $user);
	} else {
		$entity_validation = new Validation();
		$entity_validation->setValide(true);
        $entity_validation->setDateDeValidation(new \Datetime);
		$entity_validation->setType($type);
		$entity_validation->setUser($user);

		switch ($type) {
			case 'technique':
				$entity_bon->setValidationTechnique($entity_validation);
				break;
			case 'sav':
				$entity_bon->setValidationSAV($entity_validation);
				break;
			case 'horaire':
				$entity_bon->setValidationHoraire($entity_validation);
				break;
			case 'facturation':
				$entity_bon->setValidationFacturation($entity_validation);
				break;
			default:
				break;
		}
	    $em->persist($entity_bon);
	}
	$em->flush();
    return new Response();
}

// Fonction qui enregistre les informations de dévalidation 
private function devalidation($entity_validation, $entity_user) {
	$entity_validation->setValide(false);
	$entity_validation->setDateDeValidation(new \Datetime());
	$entity_validation->setUser($entity_user);
	return $entity_validation;
}


public function archiveUnFichierDeBonAction() {
    $id_fichier_bon = $_POST['identifiant_fichier'];
    $em = $this->getDoctrine()->getManager();
    $entity_fichier = $em->getRepository('LciBoilerBoxBundle:Fichier')->find($id_fichier_bon);
	if ($entity_fichier->getArchive() == false) {
		$message_archivage = "Archivé par ".$this->get('security.token_storage')->getToken()->getUser()->getLabel()." le ".date('d/m/Y à H:i');
		$entity_fichier->setArchive(true);
		$entity_fichier->setInformations($message_archivage);
	} else {
		$message_archivage = "Désarchivé par ".$this->get('security.token_storage')->getToken()->getUser()->getLabel()." le ".date('d/m/Y à H:i');
		$entity_fichier->setArchive(false);
		$entity_fichier->setInformations($message_archivage);
	}
	$em->flush();
	return new Response();
}


public function getSiteBAEntityAction() {
	$tab_fichier = null;
	if (isset($_POST['id_site_ba'])){
		$id_site_ba = $_POST['id_site_ba'];
	} else {
		$id_site_ba = 1;
	}
	$em = $this->getDoctrine()->getManager();
	$entity_siteba = $em->getRepository('LciBoilerBoxBundle:SiteBA')->find($id_site_ba);

	$tab_siteba[] = $entity_siteba->getId();
    $tab_siteba[] = $entity_siteba->getIntitule();
	$tab_siteba[] = $entity_siteba->getAdresse();
	$tab_siteba[] = $this->putZoomApi($this->putApiKey($this->transformeUrlReverse($entity_siteba->getLienGoogle())));
	$tab_siteba[] = $entity_siteba->getContact();
	$tab_siteba[] = $entity_siteba->getEmailContact();
	$tab_siteba[] = $entity_siteba->getTelContact();
	$tab_siteba[] = $entity_siteba->getInformationsClient();
	foreach ($entity_siteba->getFichiersJoint() as $ent_fichier) {
		$tab_fichier[] = $ent_fichier->getAlt();
	}
	if ($tab_fichier != null) {
		$tab_siteba[] = $tab_fichier;
	} else {
		$tab_siteba[] = null;
	}

	
	echo json_encode($tab_siteba);
	return new Response;
}



// Fonction qui récupère l'url retournée par l'entité et extrait la latitude et la longitude
private function transformeUrlReverse($url) {
	// https://www.google.com/maps/embed/v1/view?key=AIzaSyA4ceVB6W6udd67ihnRTeR_Oiip9tY_87s&center=49.26347329999999,-123.14046880000001&zoom=ZOOMAPI&maptype=satellite
	$patternLatLng = '$^https?://www.google.com/maps/embed/v1/view\?key=APIKEY&center=(.+?),(.+?)&zoom.*$';
	$patternPlace = '$^https://www.google.com/maps/embed/v1/place\?key=APIKEY&q=(.+?)&zoom=.*$';
    if (preg_match($patternLatLng, $url, $matches)) {
        return 'latLng('.$matches[1].','.$matches[2].')';
    } else if  (preg_match($patternPlace, $url, $matches)) {
		return 'https://www.google.com/maps/place/'.$matches[1].'/';
	} else {
		return $url;
	}
}


private function putApiKey($url) {
	$apiKey = $this->get('lci_boilerbox.configuration')->getEntiteDeConfiguration('cle_api_google')->getValeur();
	$pattern = '/APIKEY/';
	return (preg_replace($pattern, $apiKey, $url));
}


private function putZoomApi($url) {
    $zoomApi = $this->get('lci_boilerbox.configuration')->getEntiteDeConfiguration('zoom_api')->getValeur();
    $pattern = '/ZOOMAPI/';
    return (preg_replace($pattern, $zoomApi, $url));
}


private function sendMailDevalidation($type, $entity_bon, $entities_users, $entity_devalideur) {
	$sujet = 'Dévalidation '.$type;
	$titre = 'Dévalidation '.$type." pour l'affaire ".$entity_bon->getNumeroAffaire();
	
    $tab_destinataires = [];
    foreach ($entities_users as $entity_user) {
            $tab_destinataires[] = $entity_user->getEmail();
    }

    $service_mail = $this->get('lci_boilerbox.mailing');

    $tab_contenu = array();
	$tab_contenu[] = '%PLe '.date('d-m-Y à H\hi');
	$tab_contenu[] = ':%P';	
	$tab_contenu[] = '%PUne dévalidation ';
	$tab_contenu[] = '%G'.$type.' ';
	$tab_contenu[] = " a été effectuée sur le bon d'attachement de numéro ".$entity_bon->getNumeroBA(). ' par '.$entity_devalideur->getUsername();
	$tab_contenu[] = '%M ('.$entity_devalideur->getEmail().')';
	$tab_contenu[] = '.%P';
    $tab_contenu[] = "<br /><br /><br />";
    $tab_contenu[] = "Cordialement.<br /><br />";
    $tab_contenu[] = "Assistance BoilerBox.<br />";
	$tab_contenu[] = "%MAssistance_IBC@lci-group.fr";
    $tab_contenu[] = "<br /><br /><br />";

    $service_mail->sendMailMultiDestinataires($sujet, $titre, $tab_contenu, $tab_destinataires);
    return 0;
}

}
