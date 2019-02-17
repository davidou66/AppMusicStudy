<?php

namespace MusicStudyBundle\Controller;

use MusicStudyBundle\Service\PaginatorService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MusicStudyBundle\Service\StatService;
use MusicStudyBundle\Service\DocumentService;
use MusicStudyBundle\Service\TaskService;

class DefaultController extends Controller
{
    /**
     * @var StatService
     */
    private $statService;

    /**
     * @var DocumentService
     */
    private $documentService;

    /**
     * @var TaskService
     */
    private $taskService;

    /**
     * @var PaginatorService
     */
    private $paginatorService;

    /**
     * @param StatService $statService
     * @param DocumentService $documentService
     * @param TaskService $taskService
     * @param PaginatorService $paginatorService
     */
    public function __construct(StatService $statService, DocumentService $documentService, TaskService $taskService, PaginatorService $paginatorService)
    {
        $this->statService = $statService;
        $this->documentService = $documentService;
        $this->taskService = $taskService;
        $this->paginatorService = $paginatorService;
    }

    /**
     * @Route("/", name="dashboard")
     */
    public function indexAction()
    {
        $statsDashboard = $this->statService->getStatsDashboard();
        $statsDocuments = $this->documentService->getStatsDocuments();

        $paginateTasks = $this->paginatorService->paginate(
            $this->taskService->findTasksByUser($this->getUser()->getId(), true, true)
        );

        return $this->render('MusicStudyBundle/Default/index.html.twig',
            array(
                "countUsers" => $statsDashboard['countUsers'],
                "utilisateurs" => $statsDashboard['utilisateurs'],
                "lastUsers" => $statsDashboard['lastUsers'],
                "countDocumentsUser" => $statsDocuments['countDocumentsUser'],
                'paginateTasks' => $paginateTasks
            ));
    }

    /**
     * @Route("/stats", name="stats")
     */
    public function chartsAction()
    {
        $user = $this->getUser();
        $statsTypesCours = $this->documentService->getStatsTypesDocuments($user);

        return $this->render('MusicStudyBundle/Component/charts.html.twig',
            array(
                "stats"=>$statsTypesCours
            )
        );
    }

}
