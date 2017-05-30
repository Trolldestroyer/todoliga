<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Jugador;
use AppBundle\Form\JugadorType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class JugadorController extends Controller
{
    /**
     * @Route("/{slug}.html", name="app_jugador_jugadores")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexPlayerAction($slug)
    {
        $m = $this->getDoctrine()->getManager();
        $repo=$m->getRepository('AppBundle:Equipo');
        $equipo = $repo->find($slug);
        return $this->render(':jugador:jugador.html.twig',
            [
                'equipo'=> $equipo,
            ]
        );
    }

    /**
     * @Route("/insertJugador/{id}", name="app_jugador_insertJugador")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function insertJugadorAction($id, Request $request)
    {
        $c = new Jugador();
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        $form = $this->createForm(JugadorType::class, $c);
        if ($request->getMethod() == Request::METHOD_POST) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $m = $this->getDoctrine()->getManager();
                $repo = $m->getRepository('AppBundle:Equipo');
                $equipo = $repo->find($id);
                $user = $this->get('security.token_storage')->getToken()->getUser();
                $creator= $equipo->getCreador().$id;
                $current = $this->getUser().$id;
                if (($current!=$creator)&&(!$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))) {
                    throw $this->createAccessDeniedException();
                }
                $c->setCreador($user);
                $c->setEquipo($equipo);
                $m->persist($c);
                $m->flush();
                return $this->redirectToRoute('app_equipo_showEquipo', ['slug' => $id]);
            }
        }
        return $this->render(':jugador:form.html.twig', [
            'form' => $form->createView(),
            'action'=>  $this->generateUrl('app_jugador_insertJugador',['id' => $id])

        ]);
    }

    /**
     * @Route("/removeJugador/{id}", name="app_jugador_removeJugador")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function removeJugadorAction($id)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        $m = $this->getDoctrine()->getManager();
        $repo = $m->getRepository('AppBundle:Jugador');
        $jugador = $repo->find($id);
        $equipo = $jugador->getEquipo();
        $equipoid = $equipo->getID();
        $creator= $jugador->getCreador().$id;
        $current = $this->getUser().$id;

        if (($current!=$creator)&&(!$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))) {
            throw $this->createAccessDeniedException();
        }
        $m->remove($jugador);
        $m->flush();
        return $this->redirectToRoute('app_equipo_showEquipo',array('slug' => $equipoid));

    }


    /**
     * @Route("/updateJugador/{id}", name="app_jugador_updateJugador")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateJugadorAction($id, Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $m = $this->getDoctrine()->getManager();
        $repo = $m->getRepository('AppBundle:Jugador');
        $jugador=$repo->find($id);
        $equipo = $jugador->getEquipo();
        $equipoid = $equipo->getID();
        $form = $this->createForm(JugadorType::class, $jugador);
        if ($request->getMethod() == Request::METHOD_POST) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $m->persist($jugador);
                $m->flush();
                return $this->redirectToRoute('app_equipo_showEquipo', ['slug' => $equipoid]);
            }
        }
        return $this->render(':jugador:form.html.twig', [
            'form' => $form->createView(),
            'action'=> $this->generateUrl('app_jugador_updateJugador',['id'=>$id]),
        ]);
    }

    /**
     * @Route("detail/{slug}.html", name="app_jugador_showJugador")
     */
    public function showJugadorAction($slug)
    {
        $m = $this->getDoctrine()->getManager();
        $repository= $m->getRepository('AppBundle:Jugador');
        $jugador=$repository->find($slug);
        return $this->render(':jugador:jugador.html.twig', [
            'jugador'   => $jugador,
        ]);
    }


    /**
     * @Route("subir/{id}", name="app_jugador_ganarPunto")
     */
    public function ganarPuntoAction($id)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        $m = $this->getDoctrine()->getManager();
        $repo = $m->getRepository('AppBundle:Jugador');
        $jugador = $repo->find($id);
        $jugadorid = $jugador->getId();
        $ganar = $jugador->ganarPunto();
        $creator= $jugador->getCreador().$id;
        $current = $this->getUser().$id;
        if (($current!=$creator)&&(!$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))) {
            throw $this->createAccessDeniedException();
        }
        $m->persist($ganar);
        $m->flush();
        return $this->redirectToRoute('app_jugador_showJugador', ['slug' => $jugadorid]);
    }
    /**
     * @Route("bajar/{id}", name="app_jugador_quitarPunto")
     */
    public function quitarPuntoAction($id)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        $m = $this->getDoctrine()->getManager();
        $repo = $m->getRepository('AppBundle:Jugador');
        $jugador = $repo->find($id);
        $jugadorid = $jugador->getId();
        $ganar = $jugador->restarPunto();
        $creator= $jugador->getCreador().$id;
        $current = $this->getUser().$id;
        if (($current!=$creator)&&(!$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))) {
            throw $this->createAccessDeniedException();
        }
        $m->persist($ganar);
        $m->flush();
        return $this->redirectToRoute('app_jugador_showJugador', ['slug' => $jugadorid]);
    }
}
