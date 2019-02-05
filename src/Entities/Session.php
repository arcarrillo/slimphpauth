<?php
namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * App\Entities\Session
 * 
 * @ORM\Entity 
 * @ORM\Table(name="session")
 **/
class Session{

    /** 
     * @ORM\Id 
     * @ORM\Column(type="integer") 
     * @ORM\GeneratedValue 
     **/
    public $id;
    /** @ORM\Column(type="string") **/
    public $guid;
    /** @ORM\Column(type="datetime") **/
    public $start;
    /** @ORM\Column(type="boolean") **/
    public $active;
    /** @ORM\Column(type="integer") **/
    public $user_id;
    /** @ORM\Column(type="string") **/
    public $token;
    /** @ORM\Column(type="string", nullable = true) **/
    public $refresh_token;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entities\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    public $user;
}