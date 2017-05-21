<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Equipo;
use AppBundle\Form\EquipoType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EquipoController extends Controller
{
    /**
     * @Route("/{slug}.html", name="app_equipo_equipos")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($slug)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        $m = $this->getDoctrine()->getManager();
        $repo=$m->getRepository('AppBundle:Liga');
        $liga = $repo->find($slug);
        return $this->render(':equipo:equipos.html.twig',
            [
                'liga'=> $liga,
            ]
        );
    }

    /**
     * @Route("/insertEquipo/{id}", name="app_equipo_insertEquipo")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function insertEquipoAction($id, Request $request)
    {
        $c = new Equipo();
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        $form = $this->createForm(EquipoType::class, $c);
        if ($request->getMethod() == Request::METHOD_POST) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $m = $this->getDoctrine()->getManager();
                $repo = $m->getRepository('AppBundle:Liga');
                $liga = $repo->find($id);
                $user = $this->get('security.token_storage')->getToken()->getUser();
                $c->setCreador($user);
                $c->setLiga($liga);
                $m->persist($c);
                $m->flush();
                return $this->redirectToRoute('app_equipo_equipos', ['slug' => $id]);
            }
        }
        return $this->render(':equipo:form.html.twig', [
            'form' => $form->createView(),
            'action'=>  $this->generateUrl('app_equipo_insertEquipo',['id' => $id])
            ]
        );
    }

    /**
     * @Route("/removeEquipo/{id}", name="app_equipo_removeEquipo")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function removeEquipoAction($id)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        $m = $this->getDoctrine()->getManager();
        $repo = $m->getRepository('AppBundle:Equipo');
        $equipo = $repo->find($id);
        $liga = $equipo->getLiga();
        $ligaid = $liga->getId();
        $creator= $equipo->getCreador().$id;
        $current = $this->getUser().$id;
        if (($current!=$creator)&&(!$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))) {
            throw $this->createAccessDeniedException();
        }
        $m->remove($equipo);
        $m->flush();
        return $this->redirectToRoute('app_equipo_equipos', ['slug' => $ligaid]);
    }

    /**
     * @Route("/updateEquipo/{id}", name="app_equipo_updateEquipo")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateEquipoAction($id, Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        $m = $this->getDoctrine()->getManager();
        $repo = $m->getRepository('AppBundle:Equipo');
        $equipo=$repo->find($id);
        $liga = $equipo->getLiga();
        $ligaid = $liga->getId();
        $form = $this->createForm(EquipoType::class, $equipo);
        if ($request->getMethod() == Request::METHOD_POST) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $m->persist($equipo);
                $m->flush();
                return $this->redirectToRoute('app_equipo_equipos', ['slug' => $ligaid]);
            }
        }
        return $this->render(':equipo:form.html.twig', [
                'form'=> $form->createView(),
                'action'=> $this->generateUrl('app_equipo_updateEquipo',['id'=>$id]),
            ]
        );
    }

    /**
     * @Route("/{slug}.html", name="app_equipo_showLiga")
     */
    public function showLigaAction($slug)
    {
        $m = $this->getDoctrine()->getManager();
        $repository= $m->getRepository('AppBundle:Liga');
        $liga=$repository->find($slug);
        return $this->render(':liga:liga.html.twig', [
            'liga'   => $liga,
        ]);
    }

    /**
     * @Route("/usuario/{slug}.html", name="app_usuario_show")
     *
     */
    public function showUserAction($slug)
    {
        $m = $this ->getDoctrine()->getManager();
        $repository= $m->getRepository('UserBundle:User');
        $usuario=$repository->find($slug);
        return $this->render('usuario/usuario.html.twig',[
            'usuario' => $usuario,
        ]);
    }


}