<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Image;
use AppBundle\Form\ImageType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends Controller
{
    /**
     * @Route("/", name="app_index_index")
     */
    public function indexAction()
       {
           if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
               return $this->render(':index:index.html.twig');
           }
           return $this ->redirectToRoute('app_liga_ligas');
       }

    /**
     * @Route("/upload", name="app_index_upload")
     */
    public function uploadAction(Request $request)
    {
        $p = new Image();
        $form = $this->createForm(ImageType::class, $p);

        if ($request->getMethod() == Request::METHOD_POST) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $m = $this->getDoctrine()->getManager();
                $m->persist($p);
                $m->flush();

                return $this->redirectToRoute('app_index_index');
            }
        }

        return $this->render(':index:upload.html.twig', [
            'form' => $form->createView(),
        ]);
    }

//////////////////////VerCreador/////////////////////////////////////
    /**                                                            //
     * @Route("/usuario/{slug}.html", name="app_usuario_show")     //
     *                                                             //
     */                                                            //
    public function showUserAction($slug)                          //
    {                                                              //
        $m = $this ->getDoctrine()->getManager();                  //
        $repository= $m->getRepository('UserBundle:User');         //
        $usuario=$repository->find($slug);                         //
        return $this->render('usuario/usuario.html.twig',[         //
            'usuario' => $usuario,                                 //
        ]);                                                        //
    }                                                              //
/////////////////////////////////////////////////////////////////////

}
