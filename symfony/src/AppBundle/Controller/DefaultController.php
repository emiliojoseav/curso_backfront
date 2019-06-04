<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Services\Helpers;
use AppBundle\Services\JwtAuth;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    public function loginAction(Request $request) {
      $helpers = $this->get(Helpers::class);
      // recibir json por POST
      $json = $request->get('json', null);
      // error por defecto
      $data = array(
          'status' => 'error',
          'data' => 'Incorrect email or password'
      );
      // realizar login
      if ($json != null) {
        // convertir json a objeto php
        $params = json_decode($json);
        $email = isset($params->email) ? $params->email : null;
        $password = isset($params->password) ? $params->password : null;
        // validar email
        $emailConstraint = new Assert\Email();
        $emailConstraint->message = "This email is not valid!!";
        $validate_email = $this->get("validator")->validate($email, $emailConstraint);
        if (count($validate_email) == 0 && $password != null) {
          // llamar al servicio de autentificación
          $jwt_auth = $this->get(JwtAuth::class);
          $signup = $jwt_auth->signup($email, $password);
          
          // respuesta de éxito
          $data = array(
            'status' => 'success',
            'data' => 'Correct Email',
            'signup' => $signup
          );
        }
      }
      // devolver respuetsa parseada a Json
      return $helpers->json($data);
    }
    
    public function pruebasAction() {
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
