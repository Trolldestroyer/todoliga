<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Ronda;
use AppBundle\Form\RondaType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class RondaController extends Controller
{
    /**
     * @Route("/{slug}.html", name="app_ronda_rondas")
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
        return $this->render(':ronda:rondas.html.twig',
            [
                'liga'=> $liga,
            ]
        );
    }

    /**
     * @Route("/insertRonda/{id}", name="app_ronda_insertRonda")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function insertRondaAction($id, Request $request)
    {
        $c = new Ronda();
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(RondaType::class, $c);
        if ($request->getMethod() == Request::METHOD_POST) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $m = $this->getDoctrine()->getManager();
                $repo = $m->getRepository('AppBundle:Liga');
                $liga = $repo->find($id);
                $user = $this->get('security.token_storage')->getToken()->getUser();
                $creator= $liga->getCreador().$id;
                $current = $this->getUser().$id;
                if (($current!=$creator)&&(!$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))) {
                    throw $this->createAccessDeniedException();
                }
                $c->setCreador($user);
                $c->setLiga($liga);
                $m->persist($c);
                $m->flush();
                return $this->redirectToRoute('app_ronda_ronda', ['slug' => $id]);
            }
        }
        return $this->render(':ronda:form.html.twig', [
            'form' => $form->createView(),
            'action'=>  $this->generateUrl('app_ronda_insertRonda',['id' => $id])
            ]
        );
    }

    /**
     * @Route("/removeRonda/{id}", name="app_ronda_removeRonda")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function removeRondaAction($id)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        $m = $this->getDoctrine()->getManager();
        $repo = $m->getRepository('AppBundle:Ronda');
        $ronda = $repo->find($id);
        $liga = $ronda ->getLiga();
        $ligaid = $liga->getId();
        $creator= $ronda ->getCreador().$id;
        $current = $this->getUser().$id;
        if (($current!=$creator)&&(!$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))) {
            throw $this->createAccessDeniedException();
        }
        $m->remove($ronda );
        $m->flush();
        return $this->redirectToRoute('app_ronda _rondas', ['slug' => $ligaid]);
    }

    /**
     * @Route("/updateRondas/{id}", name="app_ronda_updateRonda")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateRondasAction($id, Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        $m = $this->getDoctrine()->getManager();
        $repo = $m->getRepository('AppBundle:Ronda');
        $ronda=$repo->find($id);
        $liga = $ronda->getLiga();
        $ligaid = $liga->getId();
        $form = $this->createForm(RondaType::class, $ronda);
        if ($request->getMethod() == Request::METHOD_POST) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $m->persist($ronda);
                $m->flush();
                return $this->redirectToRoute('app_ronda_rondas', ['slug' => $ligaid]);
            }
        }
        return $this->render(':ronda:form.html.twig', [
                'form'=> $form->createView(),
                'action'=> $this->generateUrl('app_ronda_updateRonda',['id'=>$id]),
            ]
        );
    }

    /**
     * @Route("detail/{slug}.html", name="app_ronda_showRonda")
     */
    public function showRondaAction($slug)
    {
        $m = $this->getDoctrine()->getManager();
        $repository= $m->getRepository('AppBundle:Ronda');
        $ronda=$repository->find($slug);
        return $this->render(':ronda:ronda.html.twig', [
            'ronda'   => $ronda,
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
