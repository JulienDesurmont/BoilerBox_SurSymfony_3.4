<?php
// srv/www/htdocs/Symfony/src/Lci/UserBundle/Listener/detectLoginListener.php
namespace Lci\UserBundle\Listeners;

use Symfony\Component\Security\Core\Event\AuthenticationEvent;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\HttpFoundation\Session\Session;


class detectLoginListener {
    // Login à capturer
    protected $detectLogin;
    protected $dateConnexion;
	protected $session;
	protected $serviceLog;

    public function __construct(detectLogin $detectLogin, Session $session, $serviceLog) {
        $this->detectLogin = $detectLogin;
		$this->session = $session;
        $date = new \Datetime();
        $this->dateConnexion = $date->format('d-m-Y à H:i:s');
		$this->serviceLog = $serviceLog;
    }

/*
    public function successLogin(AuthenticationEvent $event) {
		if ($_SERVER['PATH_INFO'] == '/login_check') {
        	$token = $event->getAuthenticationToken();
        	// Modification des informations
        	$this->detectLogin->enregistreConnexion('SUCCESS', $this->dateConnexion, $token);
			$this->detectLogin->verif_totp_key($token->getUser());
		}
    }
*/

    public function successLogin(InteractiveLoginEvent $event) {
		$this->serviceLog->setLog('Test','connexions.log');
        if ($_SERVER['PATH_INFO'] == '/login_check') {
            $token = $event->getAuthenticationToken();
            // Modification des informations
            $this->detectLogin->enregistreConnexion('SUCCESS', $this->dateConnexion, $token);
            $this->detectLogin->verif_totp_key($this->session, $token->getUser());
		}
    }


    public function failedLogin(AuthenticationEvent $event) {
		if ($_SERVER['PATH_INFO'] == '/login_check') {
        	$token = $event->getAuthenticationException();
        	$this->detectLogin->enregistreConnexion('FAILED', $this->dateConnexion, $token);
		}
    }
}

