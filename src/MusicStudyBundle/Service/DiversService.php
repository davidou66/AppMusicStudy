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

class DiversService
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

    /**
     * @param Utilisateur $user
     * @return array
     */
    public function getStatsTypesDocuments(Utilisateur $user)
    {
        $qb = $this->em->getRepository('MusicStudyBundle:Document')->createQueryBuilder('d')
            ->select('COUNT(d) as nbDocuments, d.type')
            ->join('d.utilisateur', 'u')
            ->where('u.id = :idUser')
            ->setParameter('idUser', $user->getId())
            ->groupBy('d.type');

        return $qb->getQuery()->getResult();
    }

    /**
     * @param QueryBuilder $data
     * @param int $limit
     * @param int $offset
     * @return Pagerfanta
     */
    public function paginate(QueryBuilder $data, $limit = 20, $offset = 1)
    {
        if($limit === null) $limit = 20;
        if($offset === null) $offset = 1;

        $adapter = new DoctrineORMAdapter($data);

        $pagerfanta = new Pagerfanta($adapter);
        if($pagerfanta->getNbResults() > 0){
            $pagerfanta->setMaxPerPage($limit);
            $pagerfanta->setCurrentPage($offset);
        }

        return $pagerfanta;
    }

}