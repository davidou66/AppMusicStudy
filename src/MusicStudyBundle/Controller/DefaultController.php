<?php

namespace MusicStudyBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MusicStudyBundle\Service\DiversService;
use MusicStudyBundle\Service\DocumentService;
use MusicStudyBundle\Service\TaskService;

class DefaultController extends Controller
{
    /**
     * @var DiversService
     */
    private $diversService;

    /**
     * @var DocumentService
     */
    private $documentService;

    /**
     * @var TaskService
     */
    private $taskService;

    /**
     * @param DiversService $diversService
     * @param DocumentService $documentService
     * @param TaskService $taskService
     */
    public function __construct(DiversService $diversService, DocumentService $documentService, TaskService $taskService)
    {
        $this->diversService = $diversService;
        $this->documentService = $documentService;
        $this->taskService = $taskService;
    }

    /**
     * @Route("/", name="dashboard")
     */
    public function indexAction()
    {
        $statsDashboard = $this->diversService->getStatsDashboard();
        $statsDocuments = $this->documentService->getStatsDocuments($this->getUser()->getId());
        $tasks = $this->taskService->findTasksByUser($this->getUser()->getId(), true);

        return $this->render('MusicStudyBundle/Default/index.html.twig',
            array(
                "countUsers" => $statsDashboard['countUsers'],
                "utilisateurs" => $statsDashboard['utilisateurs'],
                "lastUsers" => $statsDashboard['lastUsers'],
                "countDocuments" =>$statsDocuments['countDocuments'],
                "countDocumentsUser" =>$statsDocuments['countDocumentsUser'],
                'tasks' => $tasks
            ));
    }

    /**
     * @Route("/stats", name="stats")
     */
    public function chartsAction()
    {
        $user = $this->getUser();
        $statsTypesCours = $this->diversService->getStatsTypesDocuments($user);

//        $statsTasks = $this->get('ass_app.divers')->getStatsTasks($user);

        return $this->render('MusicStudyBundle/Component/charts.html.twig',
            array(
                "stats"=>$statsTypesCours
            ));
    }
}
