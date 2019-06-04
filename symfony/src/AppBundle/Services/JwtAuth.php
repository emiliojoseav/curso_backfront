<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Services;

use Firebase\JWT\JWT;

/**
 * Servicio de autentificación de login
 *
 * @author Emilio
 */
class JwtAuth {
  
  public $manager;
  
  public function __construct($manager) {
    $this->manager = $manager;
  }
  
  public function signup($email, $password) {
    // recuperar el usuario que tenga el email y password indicados
    $user = $this->
            manager->
            getRepository('BackendBundle:User')->
            findOneby(array(
                "email" => $email,
                "password" => $password
            ));
    $signup = false;
    if (is_object($user)) {
      $signup = true;
    }
    // signup correcto
    if ($signup === true) {
      // generar token jwt
      // TODO
      $data = array(
          "status" => 'success',
          "user" => $user
      );
    } else {
      $data = array(
          "status" => 'error',
          "data" => 'Login failed'
      );
    }
    return $data;
  }
}
