<?php

namespace MusicStudyBundle\Controller;

use MusicStudyBundle\Service\PaginatorService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MusicStudyBundle\Service\DiversService;
use MusicStudyBundle\Service\DocumentService;
use MusicStudyBundle\Service\TaskService;
use Symfony\Component\HttpFoundation\Request;

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
     * @var PaginatorService
     */
    private $paginatorService;


    /**
     * @param DiversService $diversService
     * @param DocumentService $documentService
     * @param TaskService $taskService
     * @param PaginatorService $paginatorService
     */
    public function __construct(DiversService $diversService, DocumentService $documentService, TaskService $taskService, PaginatorService $paginatorService)
    {
        $this->diversService = $diversService;
        $this->documentService = $documentService;
        $this->taskService = $taskService;
        $this->paginatorService = $paginatorService;
    }

    /**
     * @Route("/", name="dashboard")
     */
    public function indexAction()
    {
        $statsDashboard = $this->diversService->getStatsDashboard();
        $statsDocuments = $this->documentService->getStatsDocuments($this->getUser()->getId());

        $paginateTasks = $this->paginatorService->paginate(
            $this->taskService->findTasksByUser($this->getUser()->getId(), true, true)
        );

        return $this->render('MusicStudyBundle/Default/index.html.twig',
            array(
                "countUsers" => $statsDashboard['countUsers'],
                "utilisateurs" => $statsDashboard['utilisateurs'],
                "lastUsers" => $statsDashboard['lastUsers'],
                "countDocuments" =>$statsDocuments['countDocuments'],
                "countDocumentsUser" =>$statsDocuments['countDocumentsUser'],
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

    /**
     * @Route("/tasks_list", name="tasks_list")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function taskAction(Request $request){
        $limit = $request->get('limit');
        $index = $request->get('index');

        $paginateTasks = $this->paginatorService->paginate(
            $this->taskService->findTasksByUser($this->getUser()->getId(), true, true), $limit, $index
        );

        return $this->render('MusicStudyBundle/Component/tasks.html.twig',
            array(
                "paginateTasks"=>$paginateTasks
            )
        );
    }
}
