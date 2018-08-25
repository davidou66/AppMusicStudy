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

class PaginatorService
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