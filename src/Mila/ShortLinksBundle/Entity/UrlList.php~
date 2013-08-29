<?php
 /**
 * @access public
 * @author Mirosław Puczyński <public.mila@gmail.com>
 * @license http://www.example.com/license/licencja
 * @version 1.0
 */

namespace Mila\ShortLinksBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="url_list")
 */
class UrlList {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="text")
     */
    protected $original_url;
    /**
     * @ORM\Column(type="text")
     */
    protected $generated_url;

    /**
     * @ORM\Column(type="date")
     */
    protected $date_added;
    /**
     * @ORM\Column(type="integer")
     */
    protected $id_user;


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
     * Set original_url
     *
     * @param string $originalUrl
     * @return UrlList
     */
    public function setOriginalUrl($originalUrl)
    {
        $this->original_url = $originalUrl;
    
        return $this;
    }

    /**
     * Get original_url
     *
     * @return string 
     */
    public function getOriginalUrl()
    {
        return $this->original_url;
    }

    /**
     * Set generated_url
     *
     * @param string $generatedUrl
     * @return UrlList
     */
    public function setGeneratedUrl($generatedUrl)
    {
        $this->generated_url = $generatedUrl;
    
        return $this;
    }

    /**
     * Get generated_url
     *
     * @return string 
     */
    public function getGeneratedUrl()
    {
        return $this->generated_url;
    }

    /**
     * Set date_added
     *
     * @param \DateTime $dateAdded
     * @return UrlList
     */
    public function setDateAdded($dateAdded)
    {
        $this->date_added = $dateAdded;
    
        return $this;
    }

    /**
     * Get date_added
     *
     * @return \DateTime 
     */
    public function getDateAdded()
    {
        return $this->date_added;
    }

    /**
     * Set id_user
     *
     * @param integer $idUser
     * @return UrlList
     */
    public function setIdUser($idUser)
    {
        $this->id_user = $idUser;
    
        return $this;
    }

    /**
     * Get id_user
     *
     * @return integer 
     */
    public function getIdUser()
    {
        return $this->id_user;
    }
}