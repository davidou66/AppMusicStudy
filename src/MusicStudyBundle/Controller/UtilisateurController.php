<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 17/12/2016
 * Time: 22:00
 */

namespace MusicStudyBundle\Controller;

use MusicStudyBundle\Entity\Utilisateur;
use MusicStudyBundle\Entity\Task;
use MusicStudyBundle\Form\UserType;
use MusicStudyBundle\Form\TaskType;
use MusicStudyBundle\Service\PaginatorService;
use MusicStudyBundle\Service\UtilisateurService;
use MusicStudyBundle\Service\TaskService;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class UtilisateurController extends Controller
{
    /**
     * @var UtilisateurService
     */
    private $userService;

    /**
     * @var TaskService
     */
    private $taskService;

    /**
     * @var PaginatorService
     */
    private $paginatorService;

    /**
     * @param UtilisateurService $userService
     * @param TaskService $taskService
     * @param PaginatorService $paginatorService
     */
    public function __construct(UtilisateurService $userService, TaskService $taskService, PaginatorService $paginatorService)
    {
        $this->userService = $userService;
        $this->taskService = $taskService;
        $this->paginatorService = $paginatorService;
    }

    /**
     * @Route("/users/list/{index}/{count}", name="list_user")
     * @Template("MusicStudyBundle/Utilisateur/list.html.twig")
     */
    public function listAction(Request $request, $index = 0, $count = 15)
    {
        return array('users' => $this->userService->getUtilisateurs($index, $count));
    }

    /**
     * @Route("/user/create", name="create_user")
     * @Template("MusicStudyBundle/Utilisateur/create.html.twig")
     */
    public function createUserAction(Request $request)
    {
        $user = new Utilisateur();
        $form = $this->createForm(UserType::class, $user, array(
                'mdpRequired' => true
            )
        );

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->userService->createUser($user);
                return $this->redirectToRoute('update_user', array('id' => $user->getId()));
            }
        }

        return array('form' => $form->createView(), 'form_task' => null, 'user' => $user);
    }

    /**
     * @Route("/user/update/{id}", name="update_user")
     * @Template("MusicStudyBundle/Utilisateur/create.html.twig")
     */
    public function updateUser(Request $request, $id = null)
    {
        $user = $this->userService->getUtilisateurById($id);

        if($user === null){
//            return $this->renderView("MusicStudyBundle/Component/404.html.twig");
            throw new NotFoundHttpException();
        }

        $task = new Task();
        $task->setUtilisateur($user);

        $form = $this->createForm(UserType::class, $user,
            array(
                'mdpRequired' => false
            )
        );
        $formTask = $this->createForm(TaskType::class, $task);

        $paginateTasks = $this->paginatorService->paginate(
            $this->taskService->findTasksByUser($user->getId(), true, true)
        );

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            $formTask->handleRequest($request);

            if($formTask->isValid()){
                $this->taskService->createTask($task);
            }
            if ($form->isValid()) {
                $this->userService->updateUser($user);
            }
        }

        return array('form' => $form->createView(), 'form_task' => $formTask->createView(), 'user' => $user, 'paginateTasks' => $paginateTasks);
    }

    /**
     * @Route("/password/{id}", name="generate_password_user", defaults={"id": "-1"})
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function generatePasswordAction($id)
    {

//        return new JsonResponse(array('password' => $resPass['password']));
    }



} 