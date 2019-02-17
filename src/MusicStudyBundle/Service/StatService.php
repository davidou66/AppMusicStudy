<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 18/12/2016
 * Time: 17:36
 */

namespace MusicStudyBundle\Service;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use MusicStudyBundle\Entity\Utilisateur;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

class StatService
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * UserService constructor.
     *
     * @param ObjectManager $em
     */
    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
    }

    /**
     * @return array
     */
    public function getStatsDashboard()
    {
        $users = $this->em->getRepository('MusicStudyBundle:Utilisateur')->findAll();

        $nbUsers = count($users);
        if($nbUsers>10){
            $lastUsers = array_slice($users, -($nbUsers-11));
        }else{
            $lastUsers = $users;
        }

        $statsDashboard = array(
            'countUsers'=>count($users),
            'utilisateurs' => $users,
            'lastUsers' => $lastUsers
        );
        return $statsDashboard;
    }
}