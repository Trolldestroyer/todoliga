<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Partido
 *
 * @ORM\Table(name="partido")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PartidoRepository")
 */
class Partido
{
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
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="texto", type="string", length=255)
     */
    private $texto;

    /**
     * @var string
     *
     * @ORM\Column(name="primerEquipo", type="string", length=255)
     */
    private $primerEquipo;

    /**
     * @var string
     *
     * @ORM\Column(name="segundoEquipo", type="string", length=255)
     */
    private $segundoEquipo;

    /**
     * @var int
     *
     * @ORM\Column(name="primerResultado", type="integer")
     */
    private $primerResultado;

    /**
     * @var int
     *
     * @ORM\Column(name="segundoResultado", type="integer")
     */
    private $segundoResultado;

    /**
     * @var string
     *
     * @ORM\Column(name="ganador", type="string", length=255)
     */
    private $ganador;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_encuentro", type="datetime")
     */
    private $fecha_encuentro;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime")
     */
    private $updatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Ronda", inversedBy="ronda")
     */
    private $ronda;

    /**
     * @ORM\ManyToOne(targetEntity="Trascastro\UserBundle\Entity\User", inversedBy="partidosCreados")
     */
    private $creador;

    public function __construct()
    {

        $this->primerResultado = 0;
        $this->segundoResultado = 0;
        $this->ganador = "";
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
     * @return Partido
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
     * @return Partido
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
     * Set primerEquipo
     *
     * @param string $primerEquipo
     *
     * @return Partido
     */
    public function setPrimerEquipo($primerEquipo)
    {
        $this->primerEquipo = $primerEquipo;

        return $this;
    }

    /**
     * Get primerEquipo
     *
     * @return string
     */
    public function getPrimerEquipo()
    {
        return $this->primerEquipo;
    }

    /**
     * Set segundoEquipo
     *
     * @param string $segundoEquipo
     *
     * @return Partido
     */
    public function setSegundoEquipo($segundoEquipo)
    {
        $this->segundoEquipo = $segundoEquipo;

        return $this;
    }

    /**
     * Get segundoEquipo
     *
     * @return string
     */
    public function getSegundoEquipo()
    {
        return $this->segundoEquipo;
    }

    /**
     * Set primerResultado
     *
     * @param integer $primerResultado
     *
     * @return Partido
     */
    public function setPrimerResultado($primerResultado)
    {
        $this->primerResultado = $primerResultado;

        return $this;
    }

    /**
     * Get primerResultado
     *
     * @return int
     */
    public function getPrimerResultado()
    {
        return $this->primerResultado;
    }

    /**
     * Set segundoResultado
     *
     * @param string $segundoResultado
     *
     * @return Partido
     */
    public function setSegundoResultado($segundoResultado)
    {
        $this->segundoResultado = $segundoResultado;

        return $this;
    }

    /**
     * Get segundoResultado
     *
     * @return string
     */
    public function getSegundoResultado()
    {
        return $this->segundoResultado;
    }

    /**
     * Set ganador
     *
     * @param string $ganador
     *
     * @return Partido
     */
    public function setGanador($ganador)
    {
        $this->ganador = $ganador;

        return $this;
    }

    /**
     * Get ganador
     *
     * @return string
     */
    public function getGanador()
    {
        return $this->ganador;
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
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
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
     * @return mixed
     */
    public function getCreador()
    {
        return $this->creador;
    }

    /**
     * @param mixed $creador
     */
    public function setCreador($creador)
    {
        $this->creador = $creador;
    }

    /**
     * @return \DateTime
     */
    public function getFechaEncuentro()
    {
        return $this->fecha_encuentro;
    }

    /**
     * @param \DateTime $fecha_encuentro
     */
    public function setFechaEncuentro($fecha_encuentro)
    {
        $this->fecha_encuentro = $fecha_encuentro;
    }

    /**
     * @return mixed
     */
    public function getRonda()
    {
        return $this->ronda;
    }

    /**
     * @param mixed $ronda
     */
    public function setRonda($ronda)
    {
        $this->ronda = $ronda;
    }


}

