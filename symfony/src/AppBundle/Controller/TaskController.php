<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\Task;
use AppBundle\Services\Helpers;
use AppBundle\Services\JwtAuth;

/**
 * Description of TaskController
 *
 * @author Emilio
 */
class TaskController extends Controller {
  
  public function newAction(Request $request) {
    $helpers = $this->get(Helpers::class);
    $jwt_auth = $this->get(JwtAuth::class);
    // verificar el token de login recibido
    $token = $request->get("authorization", null);
    $authCheck = $jwt_auth->checkToken($token);
    // error or defecto
    $data = array(
        'status' => 'error',
        'code' => 400,
        'msg' => 'Authorization not valid!'
    );
    // autenticacion correcta
    if ($authCheck) {
      $identity = $jwt_auth->checkToken($token, true);
      // recibir datos de la tarea
      $json = $request->get('json', null);
      if($json) {
        // crear tarea
        $params = json_decode($json);
        $createdAt = new \DateTime('now');
        $updatedAt = new \DateTime('now');
        $user_id = ($identity->sub) ? $identity->sub : null;
        $title = (isset($params->title)) ? $params->title : null;
        $description = (isset($params->description)) ? $params->description : null;
        $status = (isset($params->status)) ? $params->status : null;
        if ($user_id && $title) {
          // crear tarea
          $em = $this->getDoctrine()->getManager();
          $user = $em->
                  getRepository('BackendBundle:User')->
                  findOneBy(array(
                      'id' => $user_id
                  ));
          $task = new Task();
          $task->setCreatedAt($createdAt);
          $task->setDescription($description);
          $task->setStatus($status);
          $task->setTitle($title);
          $task->setUpdatedAt($updatedAt);
          $task->setUser($user);
          // guardar tarea en db
          $em->persist($task);
          $em->flush();
        } else {
            $data = array(
              'status' => 'error',
              'code' => 400,
              'msg' => 'Task not created, validation failed!'
          );
        }
        $data = array(
            'status' => 'success',
            'code' => 200,
            'msg' => 'Task created!',
            'data' => $task
        );
      } else {
        $data = array(
            'status' => 'error',
            'code' => 400,
            'msg' => 'Task not created, params error!'
        );
      }
    }
    return $helpers->json($data);
  }
  
}
