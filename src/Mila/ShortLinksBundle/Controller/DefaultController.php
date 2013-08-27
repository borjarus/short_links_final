<?php

namespace Mila\ShortLinksBundle\Controller;

use Mila\ShortLinksBundle\Entity\UrlList;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Mila\ShortLinksBundle\Entity\Product;
use Symfony\Component\HttpFoundation\RedirectResponse;
use \DateTime;

class DefaultController extends Controller
{
    public function indexAction( $args = null )
    {
        $msg = array();

        if ( isset( $args ) ){
            $msg[] = $args[0];
        }

        if ( $this->get('request')->getMethod() == 'POST' ){
            $request = $this->get('request')->request->all();
            var_dump($request);
            if ( $request['custom_url'] == 'wpisz tutaj własny tekst..' || $request['custom_url'] == '' ){
                if( $request['url_address'] != 'wpisz tutaj adres url..' || $request['url_address'] != ''){
                    // generate a random string
                    $generated_string = $this->generateRandomCode();


                    $url = $request['url_address'];
                    $base_url = $this->get('router')->generate( 'mila_short_links_homepage', array(), true );
                    $em = $this->getDoctrine()->getManager();
                    $repository = $em->getRepository('MilaShortLinksBundle:UrlList');
                    $is_URL = $repository->findOneBy( array( 'original_url' => $url ) );
                    //
                    if ( $is_URL == null ){
                        // check the database if there is already specified generated string
                        $is_generated_string = $repository->findOneBy( array( 'generated_url' => $generated_string ) );
                        if ( $is_generated_string == null ){
                            // if there isnt, add an entry to the database
                            $url_list = new UrlList();
                            $url_list->setOriginalUrl( $url );
                            $url_list->setGeneratedUrl( $base_url.$generated_string );
                            $url_list->setDateAdded( new DateTime() );
                            $url_list->setIdUser( 1 );

                            $em = $this->getDoctrine()->getManager();
                            $em->persist( $url_list );
                            $em->flush();
                        } else {
                            //generuj od nowa i sprawdz jeszcze raz
                        }
                    } else {
                        $msg[] = '<p class=>Wygenerowany link to: <a href="'. $is_URL->getGeneratedUrl() .'">
                            '. $is_URL->getGeneratedUrl() .'</a></p>';
                    }

                } else {
                    $msg[] = '<p class="error">Proszę o uzupełnienie pola "Generuj losowy adres" lub "wpisz swoj własny"</p>';
                }
            }
        }
        return $this->render( 'MilaShortLinksBundle:Default:index.html.twig', array( 'msg' => $msg ) );
    }

    public function redirectAction( $code ){
        // check whether the database entry exist
        $msg = array();
        $base_url = $this->get('router')->generate( 'mila_short_links_homepage', array(), true );
        $url_to_checked = $base_url.$code;

        //var_dump($url_to_checked);

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('MilaShortLinksBundle:UrlList');
        $is_URL = $repository->findOneBy( array( 'generated_url' => $url_to_checked ) );
        if ( $is_URL != null ){
            return new RedirectResponse( $is_URL->getOriginalUrl() );
        } else {
            $msg[] = '<p class="error">Podany adres: <a href="'. $url_to_checked .'">'. $url_to_checked .'</a>
                wydaje się być nieprawidłowy</p>';
            return $this->forward( 'MilaShortLinksBundle:Default:index', array( 'args' => $msg ) );
        }
        // if there is, redirect to specified address
        // if it doesnt move to the homepage and displays error

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
}
