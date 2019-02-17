<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 17/02/2019
 * Time: 18:11
 */

namespace MusicStudyBundle\Service;


use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Mapping\ClassMetadata;

class SearchService {

    /**
     * @var ObjectManager $em
     */
    private $em;

    /**
     * @var ClassMetadata $classMetadata
     */
    private $classMetadata;

    /**
     * @param ObjectManager $em
     */
    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param $needle
     * @param $class
     * @return mixed
     */
    public function search($needle, $class)
    {
        $reflectClass = new \ReflectionClass($class);

        $this->classMetadata = $this->em->getClassMetadata($reflectClass->getName());
        $metadataProps = $this->classMetadata->getFieldNames();

        //Setup QueryBuilder
        $repoName = 'MusicStudyBundle:'.$reflectClass->getShortName();
        $qb = $this->em->getRepository($repoName)->createQueryBuilder('e');

        //Conditions
        $conditions = [];

        //Base conditions
        foreach($metadataProps as $prop){
            $conditions[] = $qb->expr()->like("e.{$prop}", ":needle");
        }

        //Related conditions
        $relatedAssociationsNames = $this->classMetadata->getAssociationNames();

        foreach ($relatedAssociationsNames as $key => $relatedAssociation){
            //Join the related entity
            $qb->leftJoin("e.{$relatedAssociation}", "ass".$relatedAssociation);

            $this->computeRelation($conditions, $relatedAssociation, $qb);
        }

        $qb->setParameter(':needle', '%'.$needle.'%');

        //Add to Or
        $orX = $qb->expr()->orX();
        $orX->addMultiple($conditions);

        //Add conditions to where clause
        $qb->where($orX);

        //Ordering

        return $qb;
    }

    /**
     * Search on primary fields of related entities
     *
     * @param array $conditions
     * @param $relatedAssociation
     * @param $qb
     *
     * @return array
     */
    private function computeRelation(&$conditions, $relatedAssociation, &$qb)
    {
        $targetClassName = $this->classMetadata->getAssociationTargetClass($relatedAssociation);
        $targetClassMetadata = $this->em->getClassMetadata($targetClassName);

        foreach($targetClassMetadata->getFieldNames() as $targetMetaProp){
            $conditions[] = $qb->expr()->like("ass{$relatedAssociation}.{$targetMetaProp}", ":needle");
        }
    }

} 