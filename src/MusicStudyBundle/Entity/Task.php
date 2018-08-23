<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 17/12/2016
 * Time: 15:37
 */

namespace MusicStudyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints\DateTime;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * MusicStudyBundle\Entity\Task
 *
 * @ORM\Entity
 * @ORM\Table(name="task")
 */
class Task
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
     * @ORM\column(type="string", length=255, nullable=false)
     */
    private $content;

    /**
     * @var int
     *
     * @ORM\column(type="integer")
     */
    private $ordre;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="Utilisateur", inversedBy="tasks")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $utilisateur;

    /**
     * @var bool
     *
     * @ORM\column(type="boolean")
     */
    private $done;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $doneAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $duration;


    public function __construct()
    {
        //Set created date
        $this->setCreatedAt(new \DateTime('NOW'));
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
     * @return boolean
     */
    public function isDone()
    {
        return $this->done;
    }

    /**
     * @param boolean $done
     */
    public function setDone($done)
    {
        $this->done = $done;
    }

    /**
     * @return int
     */
    public function getOrdre()
    {
        return $this->ordre;
    }

    /**
     * @param int $ordre
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;
    }

    /**
     * @return mixed
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }

    /**
     * @param mixed $utilisateur
     */
    public function setUtilisateur($utilisateur)
    {
        $this->utilisateur = $utilisateur;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getDoneAt()
    {
        return $this->doneAt;
    }

    /**
     * @param \DateTime $doneAt
     */
    public function setDoneAt($doneAt)
    {
        $this->doneAt = $doneAt;
    }

    /**
     * @return \DateTime
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param \DateTime $duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }
}