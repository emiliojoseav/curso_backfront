<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Services\Helpers;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
    
    public function pruebaAction() {
      // recuperar los registros de la tabla users
      $em = $this->getDoctrine()->getManager();
      $userRepo = $em->getRepository('BackendBundle:User');
      $users = $userRepo->findAll();
      // devolver respuesta json con los registros devueltos
      $helpers = $this->get(Helpers::class);
      return $helpers->json(array(
          'status' => 'success',
          'users' => $users
      ));
    }      
}
