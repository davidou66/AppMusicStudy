<?php

namespace MusicStudyBundle\Controller;

use MusicStudyBundle\Service\PaginatorService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MusicStudyBundle\Service\TaskService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class TaskController extends Controller
{
    /**
     * @var TaskService
     */
    private $taskService;

    /**
     * @var PaginatorService
     */
    private $paginatorService;


    /**
     * @param TaskService $taskService
     * @param PaginatorService $paginatorService
     */
    public function __construct(TaskService $taskService, PaginatorService $paginatorService)
    {
        $this->taskService = $taskService;
        $this->paginatorService = $paginatorService;
    }

    /**
     * @Route("/tasks_list", name="tasks_list")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function taskAction(Request $request)
    {
        $tasks = $this->taskService->findTasksByUser($this->getUser()->getId(), true, true);
        $limit = 10;
        $page = $request->get('page');

        $paginateTasks = $this->paginatorService->paginate($tasks, $limit, $page);

        return $this->render('MusicStudyBundle/Component/tasks.html.twig',
            array(
                "paginateTasks" => $paginateTasks
            )
        );
    }

    /**
     * @Route("/remove_task", name="remove_task")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function removeTaskAction(Request $request)
    {
        $task = $this->taskService->getTaskById($request->get('idTask'));

        $this->taskService->deleteTask($task);

        return new Response();
    }

    /**
     * @Route("/done_task", name="done_task")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateStatusTaskAction(Request $request){
        $task = $this->taskService->getTaskById($request->get('idTask'));

        $done = $request->get('done');

        $task->setDone($done);

        $this->taskService->updateTask($task);

        return new Response();
    }
}
