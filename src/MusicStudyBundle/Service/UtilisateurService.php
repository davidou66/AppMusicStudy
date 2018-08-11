<?php
/**
 * Created by PhpStorm.
 * User: jfdamy
 * Date: 08/03/2016
 * Time: 17:36
 */

namespace MusicStudyBundle\Service;

use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Model\UserManager;
use Symfony\Component\Security\Core\Security;

use MusicStudyBundle\Entity\Utilisateur;


class UtilisateurService
{
    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var Security
     */
    private $security;

    /**
     * UserService constructor.
     *
     * @param ObjectManager $em
     * @param UserManager $userManager
     * @param Security $security
     */
    public function __construct(ObjectManager $em, UserManager $userManager, Security $security)
    {
        $this->em = $em;
        $this->userManager = $userManager;
        $this->security = $security;
    }

    /**
     * @param array $criteria
     * @param null $index
     * @param null $count
     * @return array
     */
    public function getUtilisateurs($criteria = array(), $index = null, $count = null)
    {
        if($index == null || $count == null){
            return $this->em->getRepository('MusicStudyBundle:Utilisateur')->findBy(array(), array('prenom' => 'ASC'));
        }else{
            return $this->em->getRepository('MusicStudyBundle:Utilisateur')->findBy(
                $criteria,
                array('prenom' => 'ASC'),
                $count,
                $index
            );
        }
    }

    /**
     * @param $id
     * @return null|object
     */
    public function getUtilisateurById($id)
    {
        return $this->em->getRepository('MusicStudyBundle:Utilisateur')->find($id);
    }

    /**
     * @param Utilisateur $user
     */
    public function createUser(Utilisateur $user)
    {
        $user->addRole('ROLE_ETUDIANT');
        $this->updatePasswordUser(null, $user);
        $user->setEnabled(true);
        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * @param null $id
     * @param null $user
     * @return array
     */
    public function updatePasswordUser($id = null, $user = null)
    {
        if($id !== null){// Update du password
            $user = $this->getUtilisateurById($id);
            $user->setPassword('');
            $user->setPlainPassword($user->getPlainPassword());
            $password = $user->getPlainPassword();
        }else{//On set un password par défault: username
            $password = $user->getUsername();
            $user->setPassword('');
            $user->setPlainPassword($password);
        }

        $this->userManager->updateUser($user);

        return array('password'=>$password,'user'=>$user);
    }

    public function updateUser(Utilisateur $user)
    {
        if($user->getPlainPassword() != null){
            $this->updatePasswordUser($user->getId());
        }

        if($user->getId() != null){
            $this->em->merge($user);
            $this->em->flush();
        }

    }

    /**
     * @return Utilisateur
     */
    public function getConnectedUser()
    {
        if ($this->security === null) {
            throw new \LogicException('The SecurityBundle is not registered in your application.');
        }

        $token = $this->security->getToken();
        if (null === $token) {
            return;
        }

        $user = $token->getUser();
        if (!is_object($user)) {
            // e.g. anonymous authentication
            return;
        }

        return $user;
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        $user = $this->getConnectedUser();
        return $user->hasRole('ROLE_ADMIN');
    }

}