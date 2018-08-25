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
use MusicStudyBundle\Service\UtilisateurService;

class DocumentService
{
    /**
     * @var ObjectManager
     */
    private $em;

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
     * TOUS LES DOCUMENTS QUI NE SONT PAS ASSOCIES
     * @param int $index
     * @param int $count
     * @return array
     */
    public function getDocuments($index = 0, $count = 100){
        return $this->em->getRepository('MusicStudyBundle:Document')->findBy(
            array('utilisateur'=>null, 'displayable'=>true),
            array('fileName' => 'ASC'),
            $count,
            $index
        );
    }

    /**
     * @param $id
     * @return null|object
     */
    public function getDocumentById($id){
        return $this->em->getRepository('MusicStudyBundle:Document')->find($id);
    }

    /**
     * DOCUMENTS POUR UN USER
     * @param int $index
     * @param int $count
     * @param $id
     * @return array
     */
    public function getDocumentsByUser($index = 0, $count = 100, $id){
        $qb = $this->em->getRepository('MusicStudyBundle:Document')->createQueryBuilder('d')
            ->join('d.utilisateur', 'u');
        if(!$this->userService->isAdmin()){
            $qb->where('u.id = :idUser')
                ->setParameter('idUser', $id);
        }

        $qb->andWhere('d.displayable = true')
        ->orderBy('d.updatedAt', 'DESC');
//            ->setMaxResults($count)
//            ->setFirstResult($index);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param $id
     * @return array
     */
    public function getStatsDocuments($id){
        $docByUser = $this->em->getRepository('MusicStudyBundle:Document')->createQueryBuilder('d')
            ->join('d.utilisateur', 'u')
            ->where('u.id = :idUser')
            ->setParameter('idUser', $id)
            ->getQuery()->getResult();

        $documents = $this->em->getRepository('MusicStudyBundle:Document')->findBy(
            array('utilisateur' => null)
        );

        return array('countDocuments' => count($documents), 'countDocumentsUser' => count($docByUser));
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
    public function createDocument(Document $doc){
        $this->em->persist($doc);
        $this->em->flush();
    }

    /**
     * @param $id
     */
    public function deleteDocument($id){
        $this->em->remove($this->getDocumentById($id));
        $this->em->flush();
    }

    /**
     * @param Document $doc
     */
    public function updateDocument(Document $doc){
        if($doc->getId() != null){
            $this->em->merge($doc);
            $this->em->flush();
        }
    }

}