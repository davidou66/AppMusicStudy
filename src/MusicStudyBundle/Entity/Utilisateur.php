<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 17/12/2016
 * Time: 15:37
 */

namespace MusicStudyBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * MusicStudyBundle\Entity\User
 *
 * @ORM\Entity
 * @ORM\Table(name="utilisateur")
 * @Vich\Uploadable
 */
class Utilisateur extends BaseUser
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\column(type="string", length=32, nullable=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\column(type="string", length=32, nullable=true)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\column(type="string", length=32, nullable=true,
     * columnDefinition="Enum('yellow', 'red', 'light-blue', 'white', 'green', 'aqua')")
     */
    private $color;

    /**
     * @var string
     *
     * @ORM\column(type="string", length=6, nullable=true,
     * columnDefinition="Enum('Homme', 'Femme')")
     */
    private $sexe;

    /**
     * @var string
     *
     * @ORM\Column(name="num_tel_port", type="string", length=15, nullable=true)
     */
    private $numTelPort;

    /**
     * @var date $dateDebut
     *
     * @ORM\Column(name="created_at", type="date", nullable=false)
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\column(type="string", length=255, nullable=true)
     */
    private $commentaire;

//    /**
//     * @ORM\OneToOne(targetEntity="Document", mappedBy="utilisateur", cascade={"persist", "remove"})
//     */
//    private $avatar;

    /**
     * @ORM\ManyToOne(targetEntity="Document", cascade={"persist"})
     * @ORM\JoinColumn(name="avatar_id", referencedColumnName="id")
     */
    private $avatar;

//    /**
//     * @ORM\Column(type="datetime")
//     *
//     * @var \DateTime
//     */
//    private $updatedAt;

//    /**
//     * NOTE: This is not a mapped field of entity metadata, just a simple property.
//     *
//     * @Vich\UploadableField(mapping="user_image", fileNameProperty="imageName", size="imageSize")
//     *
//     * @var File
//     */
//    private $imageFile;
//
//    /**
//     * @ORM\Column(type="string", length=255)
//     *
//     * @var string
//     */
//    private $imageName;
//
//    /**
//     * @ORM\Column(type="integer")
//     *
//     * @var integer
//     */
//    private $imageSize;
//
//    /**
//     * @ORM\Column(type="datetime")
//     *
//     * @var \DateTime
//     */
//    private $updatedAt;
//
//    /**
//     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
//     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
//     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
//     * must be able to accept an instance of 'File' as the bundle will inject one here
//     * during Doctrine hydration.
//     *
//     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
//     */
//    public function setImageFile(File $image = null)
//    {
//        $this->imageFile = $image;
//
//        if (null !== $image) {
//            // It is required that at least one field changes if you are using doctrine
//            // otherwise the event listeners won't be called and the file is lost
//            $this->updatedAt = new \DateTimeImmutable();
//        }
//}
//
//    public function getImageFile()
//    {
//        return $this->imageFile;
//    }
//
//    public function setImageName($imageName)
//    {
//        $this->imageName = $imageName;
//    }
//
//    public function getImageName()
//    {
//        return $this->imageName;
//    }
//
//    public function setImageSize($imageSize)
//    {
//        $this->imageSize = $imageSize;
//    }
//
//    public function getImageSize()
//    {
//        return $this->imageSize;
//    }


    /**
     * @ORM\OneToMany(targetEntity="Document", mappedBy="utilisateur")
     */
    private $documents;

    /**
     * @ORM\OneToMany(targetEntity="Message", mappedBy="sender")
     */
    private $messagesSend;

    /**
     * @ORM\OneToMany(targetEntity="Message", mappedBy="receiver")
     */
    private $messagesReceive;

    /**
     * @ORM\OneToMany(targetEntity="Task", mappedBy="utilisateur")
     */
    private $tasks;

    /**
     * @ORM\OneToMany(targetEntity="Event", mappedBy="utilisateur")
     */
    private $events;

    public function __toString()
    {
        return $this->getPrenom() . ' ' . $this->getNom();
    }

    public function __construct()
    {
        parent::__construct();

        //Set created date
        $this->setCreatedAt(new \DateTime('NOW'));
        $this->documents = new ArrayCollection();
    }

/*###############################################################*/
/*#########################GET / SET#############################*/
/*###############################################################*/

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * @param mixed $tasks
     */
    public function setTasks($tasks)
    {
        $this->tasks = $tasks;
    }

    public function getTasksEnded(){
        $ret = array();
        foreach($this->tasks as $task){
            if($task->getDone()){
                $ret[] = $task;
            }

        }

        return $ret;
    }

    /**
     * @return string
     */
    public function getSexe()
    {
        return $this->sexe;
    }

    /**
     * @param string $sexe
     */
    public function setSexe($sexe)
    {
        $this->sexe = $sexe;
    }

    /**
     * @return mixed
     */
    public function getMessagesReceive()
    {
        return $this->messagesReceive;
    }

    /**
     * @param mixed $messagesReceive
     */
    public function setMessagesReceive($messagesReceive)
    {
        $this->messagesReceive = $messagesReceive;
    }

    /**
     * @return mixed
     */
    public function getMessagesSend()
    {
        return $this->messagesSend;
    }

    /**
     * @param mixed $messagesSend
     */
    public function setMessagesSend($messagesSend)
    {
        $this->messagesSend = $messagesSend;
    }

    /**
     * @return string
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * @param string $commentaire
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param string $color
     */
    public function setColor($color)
    {
        $this->color = $color;
    }

    /**
     * @return date
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param date $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * @return string
     */
    public function getNumTelPort()
    {
        return $this->numTelPort;
    }

    /**
     * @param string $numTelPort
     */
    public function setNumTelPort($numTelPort)
    {
        $this->numTelPort = $numTelPort;
    }

    /**
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param string $prenom
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    /**
     * @return mixed
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param mixed $avatar
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }

}