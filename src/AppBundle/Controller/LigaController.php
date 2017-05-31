<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Liga;
use AppBundle\Form\LigaType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class LigaController extends Controller
{
    /**
     * @Route("/", name="app_liga_ligas")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        $m = $this->getDoctrine()->getManager();
        $repo=$m->getRepository('AppBundle:Liga');
        $ligas = $repo->findAll();

        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $ligas,
            $request->query->getInt('page', 1),
            Liga::PAGINATION_ITEMS,
            [
                'wrap-queries' => true,
            ]
        );
        return $this->render(':liga:ligas.html.twig',
            [
                'ligas'=> $result,
            ]
        );
    }

    /**
     * @Route("/insertLiga", name="app_liga_insertLiga")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function insertLigaAction()
    {
        $p= new Liga();
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                throw $this->createAccessDeniedException();
        }}
        $form = $this->createForm(LigaType::class, $p);
        return $this->render(':liga:form.html.twig',
            [
                'form' =>   $form->createView(),
                'action'=>  $this->generateUrl('app_liga_doinsertLiga')
            ]
        );
    }
    /**
     * @Route("/doinsertLiga", name="app_liga_doinsertLiga")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function doinsertLigaAction(Request $request)
    {
        $p=new Liga();
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $p->setCreador($user);
        //create Form
        $form=$this->createForm(LigaType::class,$p);
        $form->handleRequest($request);
        if($form->isValid()) {
            $m = $this->getDoctrine()->getManager();
            $m->persist($p);
            $m->flush();
            return $this->redirectToRoute('app_liga_ligas');
        }
        return $this->render(':liga:form.html.twig',
            [
                'form'  =>  $form->createView(),
                'action'=>  $this->generateUrl('app_liga_doinsertLiga')
            ]
        );
    }

    /**
     * @Route("/removeLiga/{id}", name="app_liga_removeLiga")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function removeLigaAction($id)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                throw $this->createAccessDeniedException();
            }}
        $m = $this->getDoctrine()->getManager();
        $repo = $m->getRepository('AppBundle:Liga');
        $liga = $repo->find($id);
        $creator= $liga->getCreador().$id;
        $current = $this->getUser().$id;

        if (($current!=$creator)&&(!$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))) {
            throw $this->createAccessDeniedException();
        }
        $m->remove($liga);
        $m->flush();
        return $this->redirectToRoute('app_liga_ligas');
    }

    /**
     * @Route("/updateLiga/{id}", name="app_liga_updateLiga")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateLigaAction($id)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                throw $this->createAccessDeniedException();
            }}
        $m=$this->getDoctrine()->getManager();
        $repo=$m->getRepository('AppBundle:Liga');
        $liga=$repo->find($id);

        $creator= $liga->getCreador().$id;
        $current = $this->getUser().$id;
        if (($current!=$creator)&&(!$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))) {
            throw $this->createAccessDeniedException();
        }

        $form=$this->createForm(LigaType::class,$liga);
        if($form->isValid()) {
            $m->flush();
            return $this->redirectToRoute('app_liga_ligas');
        }
        return $this->render(':liga:form.html.twig',
            [
                'form'=>$form->createView(),
                'action'=>$this->generateUrl('app_liga_doUpdate',['id'=>$id])
            ]
        );
    }

    /**
     * @Route("/doUpdate/{id}", name="app_liga_doUpdate")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function doUpdateAction($id,Request $request)
    {

        $m= $this->getDoctrine()->getManager();
        $repo= $m->getRepository('AppBundle:Liga');
        $liga= $repo->find($id);
        $form=$this->createForm(LigaType::class,$liga);

        //El producto es actualizado con estos datos
        $form->handleRequest($request);
        $liga->setUpdatedAt();

        if($form->isValid()){
            $m->flush();

            return $this->redirectToRoute('app_liga_ligas');
        }

        return $this->render(':liga:form.html.twig',
            [
                'form'=> $form->createView(),
                'action'=> $this->generateUrl('app_liga_doUpdate',['id'=>$id]),
            ]
        );
    }


//////////////////////VerLiga////////////////////////////////////////
    /**                                                            //
     * @Route("/{slug}.html", name="app_liga_showLiga")            //
     */                                                            //
    public function showLigaAction($slug)                          //
    {                                                              //
        $m = $this->getDoctrine()->getManager();                   //
        $repository= $m->getRepository('AppBundle:Liga');          //
        $liga=$repository->find($slug);                            //
        return $this->render(':liga:liga.html.twig', [             //
            'liga'   => $liga,                                     //
        ]);                                                        //
    }                                                              //
/////////////////////////////////////////////////////////////////////



///////////////////////////Buscador/////////////////////////////////////////////////////////////////
    /**                                                                                           //
     * @Route("/buscarLiga", name="app_liga_buscar")                                              //
     * @return \Symfony\Component\HttpFoundation\Response                                         //
     */                                                                                           //
    public function searchAction(Request $request)                                                //
    {                                                                                             //
        $busqueda = $_POST['busqueda'];                                                           //
        if ($busqueda==''){                                                                       //
            return $this->redirectToRoute('app_index_index');                                     //
        }                                                                                         //
        return $this->redirectToRoute('app_textoLiga_show', ['palabra' => $busqueda]);            //
    }                                                                                             //
    /**                                                                                           //
     * @Route("/busquedaPorTitulo/{palabra}", name="app_textoLiga_show")                          //
     * @return \Symfony\Component\HttpFoundation\Response                                         //
     */                                                                                           //
    public function textoPalabraAction($palabra, Request $request)                                //
    {                                                                                             //
        $em = $this->getDoctrine()->getManager();                                                 //
        $bligas =$em->getRepository('AppBundle:Liga')->buscarTitulo($palabra);                    //
        return $this->render(':busqueda:busquedaLiga.html.twig',                                  //
            [                                                                                     //
                'bligas' => $bligas,                                                              //
            ]                                                                                     //
        );                                                                                        //
    }                                                                                             //
    ////////////////////////////////////////////////////////////////////////////////////////////////
}
