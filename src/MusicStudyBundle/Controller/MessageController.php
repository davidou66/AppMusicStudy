<?php

namespace MusicStudyBundle\Controller;

use MusicStudyBundle\Entity\Message;
use MusicStudyBundle\Service\MessageService;
use MusicStudyBundle\Service\UtilisateurService;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class MessageController extends Controller
{

    /**
     * @var MessageService
     */
    private $messageService;

    /**
     * @var UtilisateurService
     */
    private $userService;

    /**
     * @param MessageService $messageService
     * @param UtilisateurService $userService
     */
    public function __construct(MessageService $messageService, UtilisateurService $userService)
    {
        $this->messageService = $messageService;
        $this->userService = $userService;
    }

    /**
     * @Route("/message/last", name="get_last_message")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function getLastMessageAction(Request $request)
    {
        $messages = $this->messageService->getLastMessageByUser($request->get('idSender'), $request->get('idReceiver')); //Tous les messages pour un user

        if(!empty($messages)){
            if($request->get('conv')==true && $messages[0]->getId() != $request->get('idLastMessage')){//le dernier message n'est pas déjà affiché
                return new JsonResponse(array('html' => $this->renderView("MusicStudyBundle/Component/chatLine.html.twig", array("direction"=>"receiver", "message"=>$messages[0]))));
            }
            $message = $messages[0];
            $content = $message->getContent();
            $date  = $message->getCreatedAt()->format('d/m/Y');
        }else{
            $content = null;
            $date = null;
        }


        return new JsonResponse(array("content"=>$content, "date"=>$date));
    }

    /**
     * @Route("/message/conv", name="get_conversation")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function getConversationAction(Request $request)
    {
        $idSender = $request->get('idSender');//L'utilisateur qui est connecté
        $idReceiver = $request->get('idReceiver');//L'utilisateur qui reçoit le message

        $messages = array_reverse($this->messageService->getMessages(0, 20, $idSender , $idReceiver)); //Conversation des deux users

        $html = "";
        if($idReceiver != null){
            foreach($messages as $message){
                if($message->getSender()->getId()==$idSender){
                    $html .= $this->renderView("MusicStudyBundle/Component/chatLine.html.twig", array("direction"=>"sender", "message"=>$message));
                }else{
                    $html .= $this->renderView("MusicStudyBundle/Component/chatLine.html.twig", array("direction"=>"receiver", "message"=>$message));
                }
            }
        }else{
            foreach($messages as $message){
                $html .= $this->renderView("MusicStudyBundle/Component/chatGlobalLine.html.twig", array("message"=>$message));
            }
        }

        return new JsonResponse(array('html' => $html));
    }

    /**
     * @Route("/message/send", name="send_message")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function sendMessageAction(Request $request)
    {
        $message = new Message();
        $message->setContent($request->get('content'));
        if($request->get('idReceiver') != null){//Pas dans le chat global
            $message->setReceiver($this->userService->getUtilisateurById($request->get('idReceiver')));
        }
        $message->setSender($this->userService->getUtilisateurById($request->get('idSender')));

        $this->messageService->createMessage($message);

        if($request->get('idReceiver') != null){
            $html = $this->renderView("MusicStudyBundle/Component/chatLine.html.twig", array("direction"=>"sender", "message"=>$message));
        }else{
            $html = $this->renderView("MusicStudyBundle/Component/chatGlobalLine.html.twig", array("message"=>$message));
        }

        return new JsonResponse(array('html' => $html));
    }
}
