<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\User;
use AppBundle\Services\Helpers;

/**
 * Description of UserController
 *
 * @author Emilio.Amado
 */
class UserController extends Controller {

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

}
