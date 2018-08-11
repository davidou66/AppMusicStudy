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
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints\DateTime;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * MusicStudyBundle\Entity\Document
 *
 * @ORM\Entity
 * @ORM\Table(name="document")
 * @Vich\Uploadable
 */
class Document
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
     * @ORM\column(type="string", length=32, nullable=false)
     */
    private $fileName;

    /**
     * @Vich\UploadableField(mapping="document", fileNameProperty="fileName")
     * @var File
     */
    protected $file;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var string
     *
     * @ORM\column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="Utilisateur", inversedBy="documents")
     * @ORM\JoinColumn(name="utilisateur_id", referencedColumnName="id")
     */
    private $utilisateur;

    /**
     * @var string
     *
     * @ORM\column(type="string", length=32, nullable=true,
     * columnDefinition="Enum('Cymbale', 'Caisse claire', 'Grosse caisse', 'Toms', 'Charleston', 'Ambidextrie', 'Solfège', 'Autre')")
     */
    private $type;

    /**
     * @var bool
     *
     * @ORM\column(type="boolean", options={"default" : 0})
     */
    private $displayable = 0;

    public function __construct($displayable)
    {
        $this->displayable = $displayable;
    }

/*###############################################################*/
/*#########################GET / SET#############################*/
/*###############################################################*/

    /**
     * @param File $file
     * @return Document
     */
    public function setFile(File $file = null)
    {
        $this->file = $file;

        if ($file) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime('now');
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @param string $fileName
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }

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
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return boolean
     */
    public function isDisplayable()
    {
        return $this->displayable;
    }

    /**
     * @param boolean $displayable
     */
    public function setDisplayable($displayable)
    {
        $this->displayable = $displayable;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
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

}