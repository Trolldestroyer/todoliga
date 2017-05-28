<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Equipo
 *
 * @ORM\Table(name="equipo")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EquipoRepository")
 */
class Equipo
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
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
     * @var int
     *
     * @ORM\Column(name="trofeos", type="integer")
     */
    private $trofeos;

    /**
     * @var string
     *
     * @ORM\Column(name="localizacion", type="string", length=255)
     */
    private $localizacion;

    /**
     * @var int
     *
     * @ORM\Column(name="puntos", type="integer")
     */
    private $puntos;

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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Liga", inversedBy="liga")
     */
    private $liga;

    /**
     * @ORM\ManyToOne(targetEntity="Trascastro\UserBundle\Entity\User", inversedBy="equiposCreados")
     */
    private $creador;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Jugador", mappedBy="equipo", cascade={"remove"})
     */
    private $jugadores;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Entrenador", mappedBy="equipo", cascade={"remove"})
     */
    private $entrenadores;

    public function __construct()
    {
        $this->puntos = 0;
        $this->trofeos =0;
        $this->jugadores= new ArrayCollection();
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
     * @return Equipo
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
     * @return Equipo
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
     * Set trofeos
     *
     * @param integer $trofeos
     *
     * @return Equipo
     */
    public function setTrofeos($trofeos)
    {
        $this->trofeos = $trofeos;

        return $this;
    }

    /**
     * Get trofeos
     *
     * @return int
     */
    public function getTrofeos()
    {
        return $this->trofeos;
    }

    /**
     * Set localizacion
     *
     * @param string $localizacion
     *
     * @return Equipo
     */
    public function setLocalizacion($localizacion)
    {
        $this->localizacion = $localizacion;

        return $this;
    }

    /**
     * Get localizacion
     *
     * @return string
     */
    public function getLocalizacion()
    {
        return $this->localizacion;
    }

    /**
     * Set puntos
     *
     * @param integer $puntos
     *
     * @return Equipo
     */
    public function setPuntos($puntos)
    {
        $this->puntos = $puntos;

        return $this;
    }


    public function ganarPunto()
    {
        $this->puntos += 1;

            return $this;
    }

    public function restarPunto()
    {
        if($this->puntos !==  0){
            $this->puntos = $this->puntos -1;
        }

        return $this;
    }

    public function ganarTrofeos()
    {
        $this->trofeos += 1;

        return $this;
    }

    public function restarTrofeos()
    {
        if($this->trofeos !==  0){
            $this->trofeos = $this->trofeos -1;
        }

        return $this;
    }
    /**
     * Get puntos
     *
     * @return int
     */
    public function getPuntos()
    {
        return $this->puntos;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Equipo
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Equipo
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
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
     * @return mixed
     */
    public function getLiga()
    {
        return $this->liga;
    }

    /**
     * @param mixed $liga
     */
    public function setLiga($liga)
    {
        $this->liga = $liga;
    }

    /**
     * @return mixed
     */
    public function getJugadores()
    {
        return $this->jugadores;
    }

    /**
     * @param mixed $jugadores
     */
    public function setJugadores($jugadores)
    {
        $this->jugadores = $jugadores;
    }

    /**
     * @return mixed
     */
    public function getEntrenadores()
    {
        return $this->entrenadores;
    }

    /**
     * @param mixed $entrenadores
     */
    public function setEntrenadores($entrenadores)
    {
        $this->entrenadores = $entrenadores;
    }


}

