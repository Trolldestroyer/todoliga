<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Entrenador;
use AppBundle\Form\EntrenadorType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EntrenadorController extends Controller
{

    /**
     * @Route("/{slug}.html", name="app_entrenador_entrenador")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexEntrenadorAction($slug)
    {
        $m = $this->getDoctrine()->getManager();
        $repo=$m->getRepository('AppBundle:Equipo');
        $equipo = $repo->find($slug);
        return $this->render(':entrenador:entrenador.html.twig',
            [
                'equipo'=> $equipo,

            ]
        );
    }


    /**
     * @Route("/insertEntrenador/{id}", name="app_entrenador_insertEntrenador")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function insertEntrenadorAction($id, Request $request)
    {
        $c = new Entrenador();
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                throw $this->createAccessDeniedException();
            }}
        $form = $this->createForm(EntrenadorType::class, $c);
        if ($request->getMethod() == Request::METHOD_POST) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $m = $this->getDoctrine()->getManager();
                $repo = $m->getRepository('AppBundle:Equipo');
                $equipo = $repo->find($id);
                $user = $this->get('security.token_storage')->getToken()->getUser();
                $c->setCreador($user);
                $c->setEquipo($equipo);
                $m->persist($c);
                $m->flush();
                return $this->redirectToRoute('app_equipo_showEquipo', ['slug' => $id]);
            }
        }
        return $this->render(':entrenador:form.html.twig', [
            'form' => $form->createView(),
            'action'=>  $this->generateUrl('app_entrenador_insertEntrenador',['id' => $id])

        ]);
    }

    /**
     * @Route("/removeEntrenador/{id}", name="app_entrenador_removeEntrenador")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function removeEntrenadorAction($id)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                throw $this->createAccessDeniedException();
            }}
        $m = $this->getDoctrine()->getManager();
        $repo = $m->getRepository('AppBundle:Entrenador');
        $entrenador = $repo->find($id);
        $equipo = $entrenador->getEquipo();
        $equipoid = $equipo->getId();
        $creator= $entrenador->getCreador().$id;
        $current = $this->getUser().$id;

        if (($current!=$creator)&&(!$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))) {
            throw $this->createAccessDeniedException();
        }
        $m->remove($entrenador);
        $m->flush();
        return $this->redirectToRoute('app_equipo_showEquipo',['slug' => $equipoid]);

    }


    /**
     * @Route("/updateEntrenador/{id}", name="app_entrenador_updateEntrenador")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateEntrenadorAction($id, Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                throw $this->createAccessDeniedException();
            }}
        $m = $this->getDoctrine()->getManager();
        $repo = $m->getRepository('AppBundle:Entrenador');
        $entrenador=$repo->find($id);
        $equipo = $entrenador->getEquipo();
        $equipoid = $equipo->getId();
        $form = $this->createForm(EntrenadorType::class, $entrenador);
        if ($request->getMethod() == Request::METHOD_POST) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $m->persist($entrenador);
                $m->flush();
                return $this->redirectToRoute('app_equipo_showEquipo', ['slug' => $equipoid]);
            }
        }
        return $this->render(':entrenador:form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("detail/{slug}.html", name="app_entrenador_showEntrenador")
     */
    public function showEntrenadorAction($slug)
    {
        $m = $this->getDoctrine()->getManager();
        $repository= $m->getRepository('AppBundle:Entrenador');
        $entrenador=$repository->find($slug);
        return $this->render(':entrenador:entrenador.html.twig', [
            'entrenador'   => $entrenador,
        ]);
    }




}
