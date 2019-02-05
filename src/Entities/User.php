<?php
namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * App\Entities\User
 * 
 * @ORM\Entity 
 * @ORM\Table(name="user")
 **/
class User{
    /** 
     * @ORM\Id 
     * @ORM\Column(type="integer") 
     * @ORM\GeneratedValue 
     **/
    public $id;
     /** @ORM\Column(type="string") **/
    public $email;
     /** @ORM\Column(type="string") **/
    public $name;
     /** @ORM\Column(type="string") **/
    public $surname;
}