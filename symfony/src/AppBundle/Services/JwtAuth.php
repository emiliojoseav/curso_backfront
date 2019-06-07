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
  public $key;
  
  public function __construct($manager) {
    $this->manager = $manager;
    $this->key = 'holaquetalsoylaclavesecreta2342346456456'; // clave de codificación del token de login
  }

  //devuelve un token jwt del usuario que se loguea, este token se usará para evitar que el mismo
  // usuario pueda volverse a loguear
  public function signup($email, $password, $getHash = null) {
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
    if ($signup) {
      // generar array para el token jwt
      $token = array(
          "sub" => $user->getId(), // datos del usuario
          "email" => $user->getEmail(),
          "name" => $user->getName(),
          "surname" => $user->getSurname(),
          "iat" => time(), // hora de la creación del token
          "exp" => time() + (7*24*60*60), // caducidad del token, 1 semana en segundos
      );
      // codificar el token
      $jwt = JWT::encode(
        $token, // el token
        $this->key, // la clave
        'HS256'); // el algoritmo de codificación
      $decode = JWT::decode($jwt, $this->key, array('HS256'));
      // si NO se solicita el hash, se devuelve el token codificado
      if ($getHash == null) {
        $data = $jwt;
     // si se solicita el hash, se devuelve el token decodificado
      } else {
        $data = $decode;
      }
    } else {
      $data = array(
          "status" => 'error',
          "data" => 'Login failed'
      );
    }
    return $data;
  }
  
  public function checkToken($jwt, $getIdentity = false) {
    // decodificar el token recibido
    $auth = false;
    try {
      $decode = JWT::decode($jwt, $this->key, array('HS256'));
    } catch(\UnexpectedValueException $e) {
      $auth = false;
    } catch(\DomainException $e) {
      $auth = false;
    }
    // verificar el objeto decode, y si hay id
    if (isset($decode) && is_object($decode) && isset($decode->sub)) {
      $auth = true;
    }
    // devolvemos el decode si se requiere identity
    if ($getIdentity) {
      return $decode;
    }
    // devolvemos auth(true/false) por defecto
    return $auth;
  }
}
