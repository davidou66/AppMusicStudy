<?php
/**
 * Created by PhpStorm.
 * User: jfdamy
 * Date: 08/03/2016
 * Time: 17:36
 */

namespace MusicStudyBundle\Service;

use Doctrine\Common\Persistence\ObjectManager;
use MusicStudyBundle\Entity\Message;

class MessageService
{
    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * DocumentService constructor.
     *
     * @param ObjectManager $em
     */
    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param int $index
     * @param int $count
     * @param $idSender
     * @param $idReceiver
     * @return array
     */
    public function getMessages($index = 0, $count = 20, $idSender, $idReceiver)
    {
        if($idReceiver != null){
            return $this->em->getRepository('MusicStudyBundle:Message')->createQueryBuilder('m')
                ->join('m.sender', 's')
                ->join('m.receiver', 'r')
                ->where('s.id = :idSender OR s.id = :idReceiver')
                ->andWhere('r.id = :idReceiver OR r.id = :idSender')
                ->setParameter('idSender', $idSender)
                ->setParameter('idReceiver', $idReceiver)
                ->orderBy('m.createdAt', 'DESC')
                ->setMaxResults($count)
                ->setFirstResult($index)
                ->getQuery()->getResult();
        }else{
            return $this->em->getRepository('MusicStudyBundle:Message')->createQueryBuilder('m')
                ->where('m.receiver is null')
                ->orderBy('m.createdAt', 'DESC')
                ->setMaxResults($count)
                ->setFirstResult($index)
                ->getQuery()->getResult();
        }
    }

    /**
     * @param $idSender
     * @param $idReceiver
     * @return array
     */
    public function getLastMessageByUser($idSender, $idReceiver)
    {
        return $this->em->getRepository('MusicStudyBundle:Message')->createQueryBuilder('m')
            ->join('m.sender', 's')
            ->join('m.receiver', 'r')
            ->where('s.id = :idSender')
            ->andWhere('r.id = :idReceiver')
            ->setParameter('idSender', $idSender)
            ->setParameter('idReceiver', $idReceiver)
            ->orderBy('m.createdAt', 'DESC')
            ->getQuery()->getResult();
    }

    /**
     * @param $id
     * @return null|object
     */
    public function getMessageById($id)
    {
        return $this->em->getRepository('MusicStudyBundle:Message')->find($id);
    }

    /**
     * @param Message $message
     */
    public function createMessage(Message $message)
    {
        $this->em->persist($message);
        $this->em->flush();
    }

}