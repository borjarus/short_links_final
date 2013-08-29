<?php

namespace Mila\ShortLinksBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Mila\ShortLinksBundle\Entity\User;
use \DateTime;

class AuthController extends Controller {
    public function loginAction() {
        $request = $this->get('request')->request->all();
        if ( $this->get('request')->getMethod() == 'POST' && !empty( $request ) ) {
            $this->checkUser( $request['login'], $request['password'] );
        }

        return $this->render('MilaShortLinksBundle:Auth:login.html.twig');
    }

    public function registerAction() {
        $request = $this->get('request')->request->all();
        if ( $this->get('request')->getMethod() == 'POST' && !empty( $request ) ) {
            $this->addUserToDB( $request );
        }
        return $this->render('MilaShortLinksBundle:Auth:register.html.twig');
    }

    public function logoutAction() {
        $session = new Session();
        $session->clear();
        return $this->redirect( $this->generateUrl("mila_short_links_homepage"));
    }

    private function checkUser( $login, $password ) {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('MilaShortLinksBundle:User');
        $user = $repository->findOneBy( array( 'username' => $login ) );
        $password = md5( $password );
        $password_db = $user->getPassword();
        if ( $password == $password_db){
            $session = new Session();
            $session->start();
            $session->set('login', $user->getUsername() );
            $session->set('user_id', $user->getId() );
        }
    }

    private function addUserToDB( $user_data ){
        $user_list = new User();
        $user_list->setUsername( $user_data['login'] );
        $user_list->setPassword( $user_data['password'] );
        $user_list->setDateAdded( new DateTime() );
        $user_list->setEmail( $user_data['email'] );

        $em = $this->getDoctrine()->getManager();
        $em->persist( $user_list );
        $em->flush();
    }
}
