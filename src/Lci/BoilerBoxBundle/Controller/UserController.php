<?php
namespace Lci\BoilerBoxBundle\Controller;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


class UserController extends Controller {

public function changePasswordAction(Request $request) {
    $user = $this->getUser();
    if (!is_object($user) || !$user instanceof UserInterface) {
        throw new AccessDeniedException('This user does not have access to this section.');
    }
    $dispatcher = $this->get('event_dispatcher');
    $event = new GetResponseUserEvent($user, $request);
    $dispatcher->dispatch(FOSUserEvents::CHANGE_PASSWORD_INITIALIZE, $event);
    if (null !== $event->getResponse()) {
        return $event->getResponse();
    }
    $formFactory = $this->get('fos_user.change_password.form.factory');
    $form = $formFactory->createForm();
    $form->setData($user);
    $form->handleRequest($request);
    if ($form->isValid()) {
        $userManager = $this->get('fos_user.user_manager');
        $event = new FormEvent($form, $request);
        $dispatcher->dispatch(FOSUserEvents::CHANGE_PASSWORD_SUCCESS, $event);
        $userManager->updateUser($user);
        if (null === $response = $event->getResponse()) {
            $url = $this->generateUrl('fos_user_security_logout');
            $response = new RedirectResponse($url);
        }
        $dispatcher->dispatch(FOSUserEvents::CHANGE_PASSWORD_COMPLETED, new FilterUserResponseEvent($user, $request, $response));
        return $response;
    }
    return $this->render('LciBoilerBoxBundle:Registration:changePassword.html.twig', array(
        'form' => $form->createView()
    ));
}

}
