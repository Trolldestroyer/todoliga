<?php
/**
 * Created by PhpStorm.
 * User: albertau
 * Date: 3/05/17
 * Time: 19:25
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Partido;
use AppBundle\Form\PartidoType;
use AppBundle\Form\PlayType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PartidoController extends Controller
{

    /**
     * @Route("/{slug}.html", name="app_partido_partidos")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexPartidoAction($slug)
    {
        $m = $this->getDoctrine()->getManager();
        $repo=$m->getRepository('AppBundle:Ronda');
        $ronda = $repo->find($slug);
        return $this->render(':partido:index.html.twig',
            [
                'ronda'=> $ronda,
            ]
        );
    }

    /**
     * @Route("/insertPartido/{id}", name="app_partido_insertPartido")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function insertPartidoAction($id, Request $request)
    {

        $c = new Partido();
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                throw $this->createAccessDeniedException();
            }}
        $form = $this->createForm(PartidoType::class, $c);
        if ($request->getMethod() == Request::METHOD_POST) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $m = $this->getDoctrine()->getManager();
                $repo = $m->getRepository('AppBundle:Ronda');
                $ronda = $repo->find($id);
                $rondaid = $ronda->getID();
                $user = $this->get('security.token_storage')->getToken()->getUser();
                $c->setCreador($user);
                $c->setRonda($ronda);
                $m->persist($c);
                $m->flush();
                return $this->redirectToRoute('app_ronda_showRonda', ['slug' => $rondaid]);
            }
        }
        return $this->render(':partido:form.html.twig', [
            'form' => $form->createView(),
            'action'=>  $this->generateUrl('app_partido_insertPartido',['id' => $id])
        ]);
    }
    /**
     * @Route("/removePartido/{id}", name="app_partido_removePartido")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function removePartidoAction($id)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                throw $this->createAccessDeniedException();
            }}
        $m = $this->getDoctrine()->getManager();
        $repo = $m->getRepository('AppBundle:Partido');
        $partido=$repo->find($id);
        $ronda = $partido->getRonda();
        $rondaid = $ronda->getID();
        $creator= $partido->getCreador().$id;
        $current = $this->getUser().$id;

        if (($current!=$creator)&&(!$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))) {
            throw $this->createAccessDeniedException();
        }
        $m->remove($partido);
        $m->flush();
        return $this->redirectToRoute('app_ronda_showRonda',array('slug' => $rondaid));

    }


    /**
     * @Route("/updatePartido/{id}", name="app_partido_updatePartido")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updatePartidoAction($id, Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                throw $this->createAccessDeniedException();
            }}
        $m = $this->getDoctrine()->getManager();
        $repo = $m->getRepository('AppBundle:Partido');
        $partido=$repo->find($id);
        $ronda = $partido->getRonda();
        $rondaid = $ronda->getID();
        $form = $this->createForm(PartidoType::class, $partido);
        if ($request->getMethod() == Request::METHOD_POST) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $m->persist($partido);
                $m->flush();
                return $this->redirectToRoute('app_ronda_showRonda', ['slug' => $rondaid]);
            }
        }
        return $this->render(':partido:form.html.twig', [
            'form' => $form->createView(),
            'action'=>  $this->generateUrl('app_partido_updatePartido',['id' => $id])

        ]);
    }

    /**
     * @Route("/jugarPartido/{id}", name="app_partido_jugarPartido")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function jugarPartidoAction($id, Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                throw $this->createAccessDeniedException();
            }}
        $m = $this->getDoctrine()->getManager();
        $repo = $m->getRepository('AppBundle:Partido');
        $partido=$repo->find($id);
        $ronda = $partido->getRonda();
        $rondaid = $ronda->getID();
        $form = $this->createForm(PlayType::class, $partido);
        if ($request->getMethod() == Request::METHOD_POST) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $firstTeam= $partido->getPrimerEquipo();
                $secondTeam= $partido->getSegundoEquipo();
                $firstresult=$partido->getPrimerResultado();
                $secondresult=$partido->getSegundoResultado();

                if($firstresult>$secondresult){
                    $partido->setGanador($firstTeam);
                }elseif($firstresult<$secondresult){
                    $partido->setGanador($secondTeam);
                }else{
                    $partido->setGanador("Empate");}

                $m->persist($partido);
                $m->flush();
                return $this->redirectToRoute('app_ronda_showRonda', ['slug' => $rondaid]);
            }
        }
        return $this->render(':partido:formplay.html.twig', [
            'form' => $form->createView(),
            'action'=>  $this->generateUrl('app_partido_jugarPartido',['id' => $id])
        ]);
    }

    /**
     * @Route("showPartido/{slug}.html", name="app_partido_showPartido")
     */
    public function showPartidoAction($slug)
    {
        $m = $this->getDoctrine()->getManager();
        $repository= $m->getRepository('AppBundle:Partido');
        $partido=$repository->find($slug);
        return $this->render(':partido:partido.html.twig', [
            'partido'   => $partido,
        ]);
    }




}