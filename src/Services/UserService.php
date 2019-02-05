<?php

namespace App\Services;

use Doctrine\ORM\EntityManager;

final class UserService{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getUserByEmail($email){
        return $this->em->createQueryBuilder()
            ->select("i")
            ->from("App\Entities\User", "i")
            ->andWhere("i.email = :email")
            ->setParameter("email", $email)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function registerUser($name, $surname, $email){
        $result = false;

        
        $this->em->getConnection()->beginTransaction();
        try{
            $user = new \App\Entities\User();

            $user->email = $email;
            $user->name = $name;
            $user->surname = $surname;

            $this->em->persist($user);
            $this->em->flush();
            $this->em->getConnection()->commit();
            $result = $user;
        } catch (Exception $excep){
            $this->em->getConnection()->rollback();
        }

        return $result;
    }
}