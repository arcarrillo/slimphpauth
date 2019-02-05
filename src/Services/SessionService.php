<?php
namespace App\Services;

use Doctrine\ORM\EntityManager;

final class SessionService{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getSession($guid){
        return $this->em->createQueryBuilder()
            ->select("i")
            ->from("App\Entities\Session", "i")
            ->andWhere("i.guid = :guid")
            ->setParameter("guid", $guid)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function newSession($user, $token, $refresh_token){
        $result = false;

        $this->em->getConnection()->beginTransaction();
        try{
            $session = new \App\Entities\Session();

            $session->guid = uniqid('', true);
            $session->start = new \DateTime();
            $session->active = true;
            $session->user_id = $user->id;
            $session->user = $user;
            $session->token = $token;
            $session->refresh_token = $refresh_token;

            $this->em->persist($session);
            $this->em->flush();
            $this->em->getConnection()->commit();
            $result = $session->guid;
        } catch (Exception $excep){
            $this->em->getConnection()->rollback();
        }

        return $result;
    }

}