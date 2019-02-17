<?php
/**
 * Created by PhpStorm.
 * User: jfdamy
 * Date: 08/03/2016
 * Time: 17:36
 */

namespace MusicStudyBundle\Service;

use Doctrine\Common\Persistence\ObjectManager;
use MusicStudyBundle\Entity\Document;
use MusicStudyBundle\Entity\Utilisateur;
use Doctrine\ORM\QueryBuilder;
use MusicStudyBundle\Service\UtilisateurService;

class DocumentService
{
    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * @var UtilisateurService
     */
    private $userService;

    /**
     * DocumentService constructor.
     *
     * @param ObjectManager $em
     * @param UtilisateurService $userService
     */
    public function __construct(ObjectManager $em, UtilisateurService $userService)
    {
        $this->em = $em;
        $this->userService = $userService;
    }

    /**
     * @param $id
     * @return null|object
     */
    public function getDocumentById($id)
    {
        return $this->em->getRepository('MusicStudyBundle:Document')->find($id);
    }

    /**
     * DOCUMENTS POUR UN USER
     *
     * @param $id
     * @param $count
     * @param $toPaginate
     *
     * @return array|QueryBuilder
     */
    public function getDocumentsByUser($id = null, $count = false, $toPaginate = false)
    {
        if($id === null){
            $id = $this->userService->getConnectedUser()->getId();
        }

        $qb = $this->em->getRepository('MusicStudyBundle:Document')->createQueryBuilder('d');
        if($count) $qb->select('count(d) as nbDocs');
        $qb->leftJoin('d.utilisateur', 'u');
        if(!$this->userService->isAdmin()) $qb->where('u.id = :idUser')->setParameter('idUser', $id);
        $qb->andWhere('d.displayable = true')
            ->orderBy('d.fileName', 'ASC')
        ;

        if($toPaginate){
            return $qb;
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @return array
     */
    public function getStatsDocuments()
    {
        $docs = $this->getDocumentsByUser(null, true, false);

        return array('countDocumentsUser' => $docs[0]['nbDocs']);
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
     * @param Document $doc
     */
    public function createDocument(Document $doc)
    {
        $this->em->persist($doc);
        $this->em->flush();
    }

    /**
     * @param $id
     */
    public function deleteDocument($id)
    {
        $this->em->remove($this->getDocumentById($id));
        $this->em->flush();
    }

    /**
     * @param Document $doc
     */
    public function updateDocument(Document $doc)
    {
        if($doc->getId() != null){
            $this->em->merge($doc);
            $this->em->flush();
        }
    }

}