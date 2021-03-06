<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Liga
 *
 * @ORM\Table(name="liga")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LigaRepository")
 */
class Liga
{

    /**
     * it rarely changes, so better define it as a constant than a parameter under parameters.yml
     */
    const PAGINATION_ITEMS = 4;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     * @Assert\NotBlank(message="Name cannot be empty")
     * @Assert\Length(
     *     min="3",
     *     max="21",
     *     minMessage="Description too short 3 or more!",
     *     maxMessage="Description too long max 21!"
     * )
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="texto", type="string", length=255)
     * @Assert\NotBlank(message="Name cannot be empty")
     * @Assert\Length(
     *     min="3",
     *     max="255",
     *     minMessage="Description too short!",
     *     maxMessage="Description too long!"
     * )
     */
    private $texto;

    /**
     * @ORM\ManyToOne(targetEntity="Trascastro\UserBundle\Entity\User", inversedBy="ligasCreadas")
     */
    private $creador;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Equipo", mappedBy="liga", cascade={"remove"})
     */
    private $equipos;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Ronda", mappedBy="liga", cascade={"remove"})
     */
    private $rondas;

    public function __construct()
    {

        $this->rondas = new ArrayCollection();
        $this->equipos = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->updatedAt = $this->createdAt;
    }
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Liga
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set texto
     *
     * @param string $texto
     *
     * @return Liga
     */
    public function setTexto($texto)
    {
        $this->texto = $texto;

        return $this;
    }

    /**
     * Get texto
     *
     * @return string
     */
    public function getTexto()
    {
        return $this->texto;
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
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt()
    {
        $this->updatedAt = new \DateTime();
    }

    /**
     * Set creador
     *
     * @param string $creador
     *
     * @return Liga
     */
    public function setCreador($creador)
    {
        $this->creador = $creador;

        return $this;
    }

    /**
     * Get creador
     *
     * @return string
     */
    public function getCreador()
    {
        return $this->creador;
    }

    /**
     * @return mixed
     */
    public function getEquipos()
    {
        return $this->equipos;
    }

    /**
     * @param mixed $equipos
     */
    public function setEquipos($equipos)
    {
        $this->equipos = $equipos;
    }

    /**
     * @return mixed
     */
    public function getRondas()
    {
        return $this->rondas;
    }

    /**
     * @param mixed $rondas
     */
    public function setRondas($rondas)
    {
        $this->rondas = $rondas;
    }


}

