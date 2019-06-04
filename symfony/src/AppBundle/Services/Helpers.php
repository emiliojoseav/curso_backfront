<?php

namespace AppBundle\Services;

/*
 * Diferentes utilidades para los servicios
 */
class Helpers {
  
  public $manager;
  
  public function __construct($manager) {
    $this->manager = $manager;
  }
  
  /**
   * Parsea en parámetro a Json
   * 
   * @param type $data
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function json($data) {
    // crear el json a partir del parámetro de la función
    $normalizers = array(new \Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer);
    $encoders = array("json" => new \Symfony\Component\Serializer\Encoder\JsonEncoder());
    $serializer = new \Symfony\Component\Serializer\Serializer($normalizers, $encoders);
    $json = $serializer->serialize($data, 'json');
    // generar la respuesta con el json creado
    $response = new \Symfony\Component\HttpFoundation\Response();
    $response->setContent($json);
    $response->headers->set('Content-Type', 'application/json');
    return $response;
  }
}
