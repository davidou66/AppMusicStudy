<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 18/12/2016
 * Time: 17:36
 */

namespace MusicStudyBundle\Service;

use Doctrine\Common\Persistence\ObjectManager;
use MusicStudyBundle\Entity\Task;

class TaskService
{
    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * TaskService constructor.
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
    public function getTaskById($id)
    {
        return $this->em->getRepository('MusicStudyBundle:Task')->find($id);
    }

    /**
     * @param Task $task
     */
    public function createTask(Task $task)
    {
        $userTasks = $this->findTasksByUser($task->getUtilisateur()->getId());
        if($userTasks == null){
           $task->setOrdre(1);
        }else{
            $task->setOrdre($userTasks[0]->getOrdre()+1);
        }
        $task->setDone(false);
        $this->em->persist($task);
        $this->em->flush();
    }

    /**
     * @param $id
     */
    public function deleteTask($id)
    {
        $task = $this->getTaskById($id);
        $this->em->remove($task);
        $this->reorder($task);
        $this->em->flush();
    }

    /**
     * @param $taskRemoved
     */
    private function reorder($taskRemoved){
        //ordonner les taches de l'utilisateur
        $tasksUser = $this->findTasksByUser($taskRemoved->getUtilisateur()->getId(), true);
    }

    /**
     * @param $id
     * @param null $getAll
     * @return array
     */
    public function findTasksByUser($id, $getAll = null){
        $qb = $this->em->getRepository('MusicStudyBundle:Task')->createQueryBuilder('t')
            ->join('t.utilisateur', 'u')
            ->where('u.id = :idUser')
            ->setParameter('idUser', $id)
            ->orderBy('t.ordre', 'DESC');
        if($getAll == null){
            $qb->setMaxResults(1);
        }

        return $qb->getQuery()->getResult();
    }

}