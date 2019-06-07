<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\User;
use AppBundle\Services\Helpers;
use AppBundle\Services\JwtAuth;

/**
 * Description of UserController
 *
 * @author Emilio.Amado
 */
class UserController extends Controller {

  /**
   * Crea un nuevo usuario
   * @param Request $request
   * @return type
   */
  public function newAction(Request $request) {
    $helpers = $this->get(Helpers::class);
    $json = $request->get('json', null);
    $params = json_decode($json);
    
    $data = array(
        'status' => 'error',
        'code' => 400,
        'msg' => 'User creation failed!'
    );
    
    if ($json) {
      $createdat = new \DateTime('now');
      $role = 'user';
      $email = (isset($params->email)) ? $params->email : null;
      $name = (isset($params->name)) ? $params->name : null;
      $surname = (isset($params->surname)) ? $params->surname : null;
      $password = (isset($params->password)) ? $params->password : null;
      // validar email
      $emailConstraint = new Assert\Email();
      $emailConstraint->message = 'This email is not valid!!';
      $validate_email = $this->get("validator")->validate($email, $emailConstraint);
      // comprobaciones
      if ($email && (count($validate_email) == 0) &&
          $name && $surname && $password) {
        // creación de usuario con los datos recibidos
        $user = new User();
        $user->setCreatedAt($createdat);
        $user->setEmail($email);
        $user->setName($name);
        $user->setPassword($password);
        $user->setRole($role);
        $user->setSurname($surname);
        // buscar usuario en db
        $em = $this->getDoctrine()->getManager();
        $isset_user = $em->
                      getRepository('BackendBundle:User')->
                      findBy(array('email' => $email));
        if (count($isset_user) == 0) {
          $em->persist($user);
          $em->flush();
          // repuesta de éxito
          $data = array(
              'status' => 'success',
              'code' => 200,
              'msg' => 'New User created!',
              'user' => $user
          );
        } else {
          $data = array(
              'status' => 'error',
              'code' => 400,
              'msg' => 'User already exists, creation failed!'
          );
        }
      }
    }
    return $helpers->json($data);
  }

  /**
   * Edita un usuario
   * @param Request $request
   * @return type
   */
  public function editAction(Request $request) {
    $helpers = $this->get(Helpers::class);
    $jwt_auth = $this->get(JwtAuth::class);
    // verificar el token de login recibido
    $token = $request->get("authorization", null);
    $authCheck = $jwt_auth->checkToken($token);
    // autenticación correcta -> edit
    if ($authCheck) {
      // sacar los datos del usuario logueado
      $em = $this->getDoctrine()->getManager();
      $identity = $jwt_auth->checkToken($token, true);
      $user = $em->getRepository('BackendBundle:User')->findOneBy(array(
          'id' => $identity->sub
      ));
      
      // recibir datos para la edición
      $json = $request->get('json', null);
      $params = json_decode($json);
      // respuesta por defecto
      $data = array(
          'status' => 'error',
          'code' => 400,
          'msg' => 'User not updated!'
      );
      
      if ($json) {
        $role = 'user';
        $email = (isset($params->email)) ? $params->email : null;
        $name = (isset($params->name)) ? $params->name : null;
        $surname = (isset($params->surname)) ? $params->surname : null;
        $password = (isset($params->password)) ? $params->password : null;
        // validar email
        $emailConstraint = new Assert\Email();
        $emailConstraint->message = 'This email is not valid!!';
        $validate_email = $this->get("validator")->validate($email, $emailConstraint);
        // comprobaciones
        if ($email && (count($validate_email) == 0) &&
            $name && $surname && $password) {
          // creación de usuario con los datos recibidos
          $user->setEmail($email);
          $user->setName($name);
          $user->setPassword($password);
          $user->setRole($role);
          $user->setSurname($surname);
          // buscar usuario en db
          $isset_user = $em->
                        getRepository('BackendBundle:User')->
                        findBy(array('email' => $email));
          if ((count($isset_user) == 0) || ($identity->email == $email)) {
            $em->persist($user);
            $em->flush();
            // repuesta de éxito
            $data = array(
                'status' => 'success',
                'code' => 200,
                'msg' => 'User updated!',
                'user' => $user
            );
          } else {
            $data = array(
                'status' => 'error',
                'code' => 400,
                'msg' => 'User not updated!'
            );
          }
        }
      }
    // autenticación incorrecta
    } else {
      $data = array(
          'status' => 'error',
          'code' => 400,
          'msg' => 'Authorization not valid!'
      );
    }

    
    return $helpers->json($data);
  }

}
