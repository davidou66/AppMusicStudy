<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 17/12/2016
 * Time: 15:37
 */

namespace MusicStudyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints\DateTime;


/**
 * MusicStudyBundle\Entity\Event
 *
 * @ORM\Entity
 * @ORM\Table(name="event")
 */
class Event
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
    private $nom;

    /**
     * @var string
     *
     * @ORM\column(type="string", length=255, nullable=false)
     */
    private $color;

    /**
     * @var bool
     *
     * @ORM\column(type="boolean")
     */
    private $allDay = true;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="Utilisateur", inversedBy="events")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $utilisateur;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $dateBegin;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $dateEnd;

    /**
     * @var bool
     *
     * @ORM\column(type="boolean")
     */
    private $keepInList = true;

    /**
     * @return boolean
     */
    public function isKeepInList()
    {
        return $this->keepInList;
    }

    /**
     * @param boolean $keepInList
     */
    public function setKeepInList($keepInList)
    {
        $this->keepInList = $keepInList;
    }

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
     * @return boolean
     */
    public function isAllDay()
    {
        return $this->allDay;
    }

    /**
     * @param boolean $allDay
     */
    public function setAllDay($allDay)
    {
        $this->allDay = $allDay;
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
     * @return \DateTime
     */
    public function getDateBegin()
    {
        return $this->dateBegin;
    }

    /**
     * @param \DateTime $dateBegin
     */
    public function setDateBegin($dateBegin)
    {
        $this->dateBegin = $dateBegin;
    }

    /**
     * @return \DateTime
     */
    public function getDateEnd()
    {
        return $this->dateEnd;
    }

    /**
     * @param \DateTime $dateEnd
     */
    public function setDateEnd($dateEnd)
    {
        $this->dateEnd = $dateEnd;
    }
}