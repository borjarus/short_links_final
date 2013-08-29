<?php

namespace Mila\ShortLinksBundle\Controller;

use Mila\ShortLinksBundle\Entity\UrlList;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Mila\ShortLinksBundle\Entity\Product;
use Symfony\Component\HttpFoundation\RedirectResponse;
use \DateTime;
use Symfony\Component\HttpFoundation\Session\Session;

class DefaultController extends Controller {
    public function indexAction( $args = null ){
        $msg = array();

        if ( isset( $args ) ){
            $msg[] = $args[0];
        }

        $session = new Session();
        $user = $session->get('login');
        if ( isset( $user ) ){
            $user = $session->get('login');
            $user_id = $session->get('user_id');
            $login_text = 'Zalogownano: '.$user.'
            <a href="'.$this->get('router')->generate( 'mila_short_links_logout', array(), true ).'">Wyloguj</a>';
        } else {
            $user = '';
            $user_id = 0;
            $login_text = '';
        }

        $request = $this->get('request')->request->all();
        if ( $this->get('request')->getMethod() == 'POST' && !empty( $request )  ){

            //clean request from default data
            if ( $request['url_address'] == 'wpisz tutaj adres url..' ) { $request['url_address'] = ''; }
            if ( $request['custom_url'] == 'wpisz tutaj własny tekst..' ) { $request['custom_url'] = ''; }
            $base_url = $this->get('router')->generate( 'mila_short_links_homepage', array(), true );
            if ( $request['custom_url'] == '' ){
                if( $request['url_address'] != ''){
                    $url = $request['url_address'];
                    $em = $this->getDoctrine()->getManager();
                    $repository = $em->getRepository('MilaShortLinksBundle:UrlList');
                    $is_URL = $repository->findOneBy( array( 'original_url' => $url ) );
                    if ( $is_URL == null ){
                        // check the database if there is already specified generated string
                        $generated_url = $this->generateAndCheckURL( $base_url );
                        if ( $user_id != 0 ){
                            $this->addURLToDB( $url, $generated_url, $user_id);
                            $msg[] = '<p>Wygenerowany link to: <a href="'. $generated_url .'">
                            '. $generated_url .'</a></p>';
                        } else {
                            $msg[] = '<p class="error">Musisz być zalogowany aby dodać shortlink</p>';
                        }

                    } else {
                        $msg[] = '<p>Wygenerowany link to: <a href="'. $is_URL->getGeneratedUrl() .'">
                            '. $is_URL->getGeneratedUrl() .'</a></p>';
                    }

                } else {
                    $msg[] = '<p class="error">Proszę o uzupełnienie pola "Generuj losowy adres" lub "wpisz swoj własny"</p>';
                }
            } else {
                if ( $request['url_address'] != '' ){
                    $checked_url = $this->checkURL( $base_url, $request['custom_url'] );
                    if ( $checked_url != null ){
                        $this->addURLToDB( $request['url_address'], $checked_url, 1);
                    } else {
                        $checked_url = $base_url.$request['custom_url'];
                    }
                    $msg[] = '<p>Wygenerowany link to: <a href="'. $checked_url .'">
                        '. $checked_url .'</a></p>';
                } else {
                    $msg[] = '<p class="error">Proszę o uzupełnienie pola "Generuj losowy adres" lub "wpisz swoj własny"</p>';
                }
            }
        }
        return $this->render( 'MilaShortLinksBundle:Default:index.html.twig',
            array( 'msg' => $msg, 'login_text' => $login_text ) );
    }

    public function redirectAction( $code ){
        // check whether the database entry exist
        $msg = array();
        $base_url = $this->get('router')->generate( 'mila_short_links_homepage', array(), true );
        $url_to_checked = $base_url.$code;

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('MilaShortLinksBundle:UrlList');
        $is_URL = $repository->findOneBy( array( 'generated_url' => $url_to_checked ) );
        if ( $is_URL != null ){
            // if there is, redirect to specified address
            $url_to_redirect = $is_URL->getOriginalUrl();
            //check if url to redirect started with http:// if not add to it
            $test = preg_match('/^https?:\/\//', $url_to_redirect);
            if ( !$test  ){
                $url_to_redirect = 'http://'.$url_to_redirect;
            }
            return new RedirectResponse( $url_to_redirect );
        } else {
            // if it doesnt move to the homepage and displays error
            $msg[] = '<p class="error">Podany adres: <a href="'. $url_to_checked .'">'. $url_to_checked .'</a>
                wydaje się być nieprawidłowy</p>';
            return $this->forward( 'MilaShortLinksBundle:Default:index', array( 'args' => $msg ) );
        }
    }

    private  function generateRandomCode( $length = 6 ){
        $valid_characters = 'abcdefghijklmnopqrstuxyvwzABCDEFGHIJKLMNOPQRSTUXYVWZ0123456789-';
        $valid_char_number = strlen( $valid_characters );

        $result = '';

        for ( $i = 0; $i < $length; $i++ ) {
            $index = mt_rand(0, $valid_char_number - 1);
            $result .= $valid_characters[$index];
        }

        return $result;
    }

    private function generateAndCheckURL( $base_url ){
        $generated_string = $this->generateRandomCode();
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('MilaShortLinksBundle:UrlList');
        $url_to_check = $base_url.$generated_string;
        $is_generated_string = $repository->findOneBy( array( 'generated_url' => $url_to_check ) );
        if ( $is_generated_string != null ){
            $this->generateAndCheckURL( $base_url );
        } else {
            return $url_to_check;
        }
    }

    private function checkURL( $base_url, $user_string ){
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('MilaShortLinksBundle:UrlList');
        $url_to_check = $base_url.$user_string;
        $is_generated_string = $repository->findOneBy( array( 'generated_url' => $url_to_check ) );
        if ( $is_generated_string != null ){
            return null;
        } else {
            return $url_to_check;
        }
    }

    private function addURLToDB( $orginal_url, $generated_url, $user_id ){
        $url_list = new UrlList();
        $url_list->setOriginalUrl( $orginal_url );
        $url_list->setGeneratedUrl( $generated_url );
        $url_list->setDateAdded( new DateTime() );
        $url_list->setIdUser( $user_id );

        $em = $this->getDoctrine()->getManager();
        $em->persist( $url_list );
        $em->flush();
    }
}
