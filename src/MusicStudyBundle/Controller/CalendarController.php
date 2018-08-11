<?php

namespace MusicStudyBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use MusicStudyBundle\Entity\Event;
use MusicStudyBundle\Service\EventService;
use MusicStudyBundle\Service\UtilisateurService;

class CalendarController extends Controller
{
    /**
     * @var EventService
     */
    private $eventService;

    /**
     * @var UtilisateurService
     */
    private $userService;

    /**
     * CalendarController constructor
     *
     * @param EventService $eventService
     * @param UtilisateurService $utilisateurService
     */
    public function __construct(EventService $eventService, UtilisateurService $utilisateurService)
    {
        $this->eventService = $eventService;
        $this->userService = $utilisateurService;

    }

    /**
     * @Route("/calendar", name="calendar")
     * @Template("MusicStudyBundle/Calendar/calendar.html.twig")
     */
    public function calendarAction(Request $request)
    {
//        $users = $this->get('ass_app.user')->getUtilisateurs();
        $newEvents = $this->eventService->getEventsToSet();//EVENT QUI NE SONT PAS ENCORE DANS LE CALENDRIER
        $events = $this->eventService->getCurrentEvents();

        return array('newEvents'=>$newEvents, "events"=>$events);
//        return array('users'=>$users, 'newEvents'=>$newEvents, "events"=>$events);
    }

    /**
     * @Route("/create/users_event", name="create_users_event")
     */
    public function createUsersEvents(){
        $users = $this->userService->getUtilisateurs();

        foreach($users as $u){
            $event = $this->eventService->getEventByUser($u);
            if($event == null){
                $newEvent = new Event();
                $newEvent->setUtilisateur($u);
                $newEvent->setNom($u->getPrenom().' '.$u->getNom());
                $newEvent->setKeepInList(true);
                $newEvent->setColor($u->getColor());
                $newEvent->setAllDay(true);
                $this->eventService->createEvent($newEvent);
            }
        }

        return new JsonResponse(array('response' => "ok"));
    }

    /**
     * @Route("/create/event", name="create_event")
     */
    public function createEventAction(Request $request)
    {
        $nom = $request->get('nom');
        $color = $request->get('color');

        if($nom == null || $nom == ''){
            return new JsonResponse(array('response' => "nok"));
        }

        $event = new Event();
        $event->setNom($nom);
        $event->setColor($color);

        $this->eventService->createEvent($event);

        return new JsonResponse(array('response' => "ok"));
    }

    /**
     * @Route("/update/event", name="update_event")
     */
    public function updateEventAction(Request $request)
    {
        $dateBegin = $request->get('dateBegin');
        $dateEnd   = $request->get('dateEnd');
        $idEvent   = $request->get('idEvent');
        $allDay    = $request->get('allDay');
        $keeInList = $request->get('keep');
        $duplicate = $request->get('duplicate');

        $event = $this->eventService->getEventById($idEvent);

        if($event !== null && $this->isGranted("ROLE_ADMIN")){
            if($dateBegin !== "" || $dateBegin !== null){
                $dateBegin = new \DateTime($dateBegin);
            }
            if($dateEnd !== "" || $dateEnd !== null){
                $dateEnd = $dateBegin;
            }

            if($duplicate){
                $copiedEvent = new Event();
                $copiedEvent->setNom($event->getNom());
                $copiedEvent->setColor($event->getColor());
                $copiedEvent->setDateBegin($dateBegin);
                $copiedEvent->setDateEnd($dateEnd);
                $copiedEvent->setAllDay($allDay);
                $copiedEvent->setKeepInList(false);
                $copiedEvent->setUtilisateur($event->getUtilisateur());

                $this->eventService->createEvent($copiedEvent);
            }else{
                $event->setDateBegin($dateBegin);
                $event->setDateEnd($dateEnd);
                $event->setAllDay($allDay);
                $event->setKeepInList($keeInList);
                $this->eventService->updateEvent($event);
            }
        }

        return new JsonResponse(array('response' => "ok"));
    }

    /**
     * @param Event $eventAlpha
     * @return Event
     */
    private function duplicateEvent(Event $eventAlpha)
    {
        $copiedEvent = new Event();
        $copiedEvent->setNom($eventAlpha->getNom());
        $copiedEvent->setColor($eventAlpha->getColor());
        $copiedEvent->setDateBegin($eventAlpha->getDateBegin());
        $copiedEvent->setDateEnd($eventAlpha->getDateEnd());
        $copiedEvent->setAllDay($eventAlpha->isAllDay());
        $copiedEvent->setKeepInList(false);
        $copiedEvent->setUtilisateur($eventAlpha->getUtilisateur());

        return $copiedEvent;
    }

    /**
     * @Route("/duplicate/event", name="duplicate_event")
     */
    public function duplicateEventAction(Request $request)
    {
        $idEvent = $request->get('idEvent');

        $event = $this->eventService->getEventById($idEvent);

        if($event !== null){
            $copyEvent = clone $event;
            $this->eventService->createEvent($copyEvent);
        }

        return new JsonResponse(array('response' => "ok"));
    }

    /**
     * @Route("/delete/event", name="delete_event")
     */
    public function deleteEventAction(Request $request)
    {
        $id = $request->get('idEvent');

        if($id == null || $id == ''){
            return new JsonResponse(array('response' => "nok"));
        }

        $this->eventService->deleteEvent($id);

        return new JsonResponse(array('response' => "ok"));
    }

}
