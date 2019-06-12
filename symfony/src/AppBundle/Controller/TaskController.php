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
  
  /**
   * Crea una acción
   * @param Request $request
   * @return type
   */
  public function newAction(Request $request) {
    $helpers = $this->get(Helpers::class);
    $jwt_auth = $this->get(JwtAuth::class);
    // verificar el token de login recibido
    $token = $request->get("authorization", null);
    $authCheck = $jwt_auth->checkToken($token);
    // autenticación incorrecta
    if (!$authCheck) {
      return $helpers->json(array(
          'status' => 'error',
          'code' => 400,
          'msg' => 'Authorization not valid!'
      ));
    }
    // info del usuario logueado
    $identity = $jwt_auth->checkToken($token, true);
    // recibir datos de la tarea
    $json = $request->get('json', null);
    // error de parámetros
    if (!$json) {
      return $helpers->json(array(
          'status' => 'error',
          'code' => 400,
          'msg' => 'Task not created, params error!'
      ));
    }
    // crear tarea
    $params = json_decode($json);
    $createdAt = new \DateTime('now');
    $updatedAt = new \DateTime('now');
    $user_id = ($identity->sub) ? $identity->sub : null;
    $title = (isset($params->title)) ? $params->title : null;
    $description = (isset($params->description)) ? $params->description : null;
    $status = (isset($params->status)) ? $params->status : null;
    // error de parámetros
    if (!$user_id || !$title) {
      return $helpers->json(array(
          'status' => 'error',
          'code' => 400,
          'msg' => 'Task not created, params error!'
      ));
    }
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
    return $helpers->json(array(
          'status' => 'success',
          'code' => 200,
          'msg' => 'Task created!',
          'data' => $task
    ));
  }
  
  /**
   * Edita una tarea
   * @param Request $request
   * @return type
   */
  public function editAction(Request $request, $id = null) {
    $helpers = $this->get(Helpers::class);
    $jwt_auth = $this->get(JwtAuth::class);
    // verificar el token de login recibido
    $token = $request->get("authorization", null);
    $authCheck = $jwt_auth->checkToken($token);
    // autenticacion incorrecta
    if (!$authCheck) {
      return $helpers->json(array(
          'status' => 'error',
          'code' => 400,
          'msg' => 'Authorization not valid!'
      ));
    }
    // info del usuario logueado
    $identity = $jwt_auth->checkToken($token, true);
    // recibir datos de la tarea
    $json = $request->get('json', null);
    // error de parámetros
    if (!$json) {
      return $helpers->json(array(
          'status' => 'error',
          'code' => 400,
          'msg' => 'Task not created, params error!'
      ));
    }
    // crear tarea
    $params = json_decode($json);
    $createdAt = new \DateTime('now');
    $updatedAt = new \DateTime('now');
    $user_id = ($identity->sub) ? $identity->sub : null;
    $title = (isset($params->title)) ? $params->title : null;
    $description = (isset($params->description)) ? $params->description : null;
    $status = (isset($params->status)) ? $params->status : null;
    // error de parámetros
    if (!$user_id || !$title) {
      return $helpers->json(array(
          'status' => 'error',
          'code' => 400,
          'msg' => 'Task not created, params error!'
      ));
    }
    $em = $this->getDoctrine()->getManager();
    $user = $em->
            getRepository('BackendBundle:User')->
            findOneBy(array(
              'id' => $user_id
            ));
    // si no hay "id", creamos la tarea
    if (!$id) {
      $task = new Task();
      $task->setCreatedAt($createdAt);
      $task->setDescription($description);
      $task->setStatus($status);
      $task->setTitle($title);
      $task->setUpdatedAt($updatedAt);
      $task->setUser($user);
      // guardar cambios en db
      $em->persist($task);
      $em->flush();
      return $helpers->json(array(
          'status' => 'success',
          'code' => 200,
          'msg' => 'Task created!',
          'data' => $task
      ));
    }
    // si hay "id", editamos la tarea
    // recuperar tarea de la db con el id facilitado
    $task = $em->
            getRepository('BackendBundle:Task')->
            findOneBy(array(
              'id' => $id
            ));
    // verificar que la tarea a editar pertenece al usuario logueado
    if (!$identity->sub ||
        !($identity->sub == $task->getUser()->getId())) {
      return $helpers->json(array(
          'status' => 'error',
          'code' => 400,
          'msg' => 'Task update error, user validation failed!'
      ));
    }
    // editar tarea
    $task->setDescription($description);
    $task->setStatus($status);
    $task->setTitle($title);
    $task->setUpdatedAt($updatedAt);
    // guardar cambios en db
    $em->persist($task);
    $em->flush();
    return $helpers->json(array(
        'status' => 'success',
        'code' => 200,
        'msg' => 'Task edited!',
        'data' => $task
    ));
  }

  /**
   * Lista todas las tareas
   * @param Request $request
   */
  public function tasksAction(Request $request) {
    $helpers = $this->get(Helpers::class);
    $jwt_auth = $this->get(JwtAuth::class);
    // verificar el token de login recibido
    $token = $request->get("authorization", null);
    $authCheck = $jwt_auth->checkToken($token);
    // autenticacion incorrecta
    if (!$authCheck) {
      return $helpers->json(array(
          'status' => 'error',
          'code' => 400,
          'msg' => 'Authorization not valid!'
      ));
    }
    // info del usuario logueado
    $identity = $jwt_auth->checkToken($token, true);
    $em = $this->getDoctrine()->getManager();
    // recuperar de db las tareas del usuario
    $dql = 'SELECT t FROM BackendBundle:Task t ORDER BY t.id DESC';
    $query = $em->createQuery($dql);
    // cargar nuestro paginador
    $page = $request->query->getInt('page', 1); // recoger la página con el listado que llega por http
    $paginator = $this->get('knp_paginator'); // cargar el paginador creado anteriormente en app/AppKernel.php
    $items_per_page = 10; // tareas por página mostrada
    $pagination = $paginator->paginate($query, $page, $items_per_page); //carga en el paginador las tareas listadas
    $total_items_count = $pagination->getTotalItemCount(); // cuenta el total de tareas listadas
    // devolver el resultado
    return $helpers->json(array(
        'status' => 'success',
        'code' => 200,
        'msg' => 'OK',
        'total_items_count' => $total_items_count,
        'page_actual' => $page,
        'items_per_page' => $items_per_page,
        'total_pages' => ceil($total_items_count / $items_per_page),
        'data' => $pagination
    ));
  }
  
  public function taskAction(Request $request, $id = null) {
    $helpers = $this->get(Helpers::class);
    $jwt_auth = $this->get(JwtAuth::class);
    // verificar el token de login recibido
    $token = $request->get("authorization", null);
    $authCheck = $jwt_auth->checkToken($token);
    // autenticacion incorrecta
    if (!$authCheck) {
      return $helpers->json(array(
                  'status' => 'error',
                  'code' => 400,
                  'msg' => 'Authorization not valid!'
      ));
    }
    // info del usuario logueado
    $identity = $jwt_auth->checkToken($token, true);
    // buscar tarea en db
    $em = $this->getDoctrine()->getManager();
    $task = $em->
            getRepository('BackendBundle:Task')->
            findOneBy(array(
              'id' => $id
            ));
    // tarea no encontrada
    if (!$task ||
        !is_object($task) ||
        !($identity->sub == $task->getUser()->getId())) {
      return $helpers->json(array(
                  'status' => 'error',
                  'code' => 404,
                  'msg' => 'User task not found'
      ));
    }
    // devolver tarea
    return $helpers->json(array(
        'status' => 'success',
        'code' => 200,
        'data' => $task
    ));
  }

  public function searchAction(Request $request, $search = null) {
    $helpers = $this->get(Helpers::class);
    $jwt_auth = $this->get(JwtAuth::class);
    // verificar el token de login recibido
    $token = $request->get("authorization", null);
    $authCheck = $jwt_auth->checkToken($token);
    // autenticacion incorrecta
    if (!$authCheck) {
      return $helpers->json(array(
          'status' => 'error',
          'code' => 400,
          'msg' => 'Authorization not valid!'
      ));
    }
    // info del usuario logueado
    $identity = $jwt_auth->checkToken($token, true);
    // filtros de tareas
    $filter = $request->get('filter', null);
    if (empty($filter)) {
      $filter = null;
    } elseif ($filter == 1) {
      $filter = 'new';
    } elseif ($filter == 2) {
      $filter = 'todo';
    } else {
      $filter = 'finished';
    }
    // orden de tareas
    $order = $request->get('order', null);
    if (empty($order) || $order == 2) {
      $order = 'DESC';
    } else {
      $order = 'ASC';
    }
    // búsqueda de tarea
    $dql = "SELECT t FROM BackendBundle:Task t ".
           "WHERE t.user = $identity->sub ";
    if ($search != null) {
      $dql .= "AND (t.title LIKE :search OR t.description LIKE :search) ";
    }
    // set filter
    if ($filter != null) {
      $dql .= "AND t.status = :filter ";
    }
    // set order
    if ($order != null) {
      $dql .= "ORDER BY t.id $order";
    }
    // ejecutar query
    $em = $this->getDoctrine()->getManager();
    $query = $em->createQuery($dql);
    // set parameter filter
    if ($filter != null) {
      $query->setParameter('filter', "$filter");
    }
    // set parameter search
    if ($search != null) {
      $query->setParameter('search', "%$search%"); // el % hace que se buscque por palabra completa o solo una parte de ella
    }
    $tasks = $query->getResult();
    return $helpers->json(array(
          'status' => 'success',
          'code' => 200,
          'data' => $tasks
      ));
  }
}
