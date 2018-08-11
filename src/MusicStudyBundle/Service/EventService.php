<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 18/12/2016
 * Time: 17:36
 */

namespace MusicStudyBundle\Service;

use Doctrine\Common\Persistence\ObjectManager;
use MusicStudyBundle\Entity\Utilisateur;
use MusicStudyBundle\Entity\Event;


class EventService
{
    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * EventService constructor.
     *
     * @param ObjectManager $em
     */
    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param $id
     * @return null|object
     */
    public function getEventById($id)
    {
        if($id !== null){
            return $this->em->getRepository('MusicStudyBundle:Event')->find($id);
        }else{
            return null;
        }
    }

    /**
     * @param Utilisateur $user
     * @return null|object
     */
    public function getEventByUser(Utilisateur $user)
    {
        return $this->em->getRepository('MusicStudyBundle:Event')->findOneBy(array("utilisateur"=>$user));
    }

    public function getAllEvents()
    {
        return $this->em->getRepository('MusicStudyBundle:Event')->findAll();
    }

    /**
     * LISTE DES EVENEMENTS A DROPPER
     **/
    public function getEventsToSet()
    {
        return $this->em->getRepository('MusicStudyBundle:Event')->createQueryBuilder('e')
//            ->where('e.dateBegin IS NOT NULL')
//            ->orWhere('e.keepInList = true')
            ->where('e.keepInList = true')
            ->orderBy('e.nom', 'ASC')
            ->getQuery()->getResult();
    }

    /**
     * LISTE DES EVENEMENTS DANS LE CALENDRIER
     * @return array
     */
    public function getCurrentEvents()
    {
        return $this->em->getRepository('MusicStudyBundle:Event')->createQueryBuilder('l')
            ->where('l.dateBegin IS NOT NULL')
//            ->where('l.dateBegin IS NOT NULL OR l.dateEnd IS NOT NULL')
            ->orderBy('l.nom', 'ASC')
            ->getQuery()->getResult();
    }

    /**
     * @param Event $event
     */
    public function createEvent(Event $event)
    {
        $this->em->persist($event);
        $this->em->flush();
    }

    /**
     * @param Event $event
     */
    public function updateEvent(Event $event)
    {
        $this->em->merge($event);
        $this->em->flush();
    }

    /**
     * @param $id
     */
    public function deleteEvent($id)
    {
        $event = $this->getEventById($id);
        $this->em->remove($event);
        $this->em->flush();
    }


}