<?php

namespace BackendBundle\Entity;

/**
 * Task
 */
class Task
{
    /**
     * @var int
     */
    private $id;
    
    /**
     * @var int
     */
    private $user_id;
    
    /**
     * @var string
     */
    private $title;
    
    /**
     * @var string
     */
    private $description;
    
    /**
     * @var string
     */
    private $status;
    
    /**
     * @var \DateTime
     */
    private $created_at;
    
    /**
     * @var \DateTime
     */
    private $updated_at;
    
    /**
     * @var \BackendBundle\Entity\User
     */
    private $user;


    function getId() {
      return $this->id;
    }

    function getUser_id() {
      return $this->user_id;
    }

    function getTitle() {
      return $this->title;
    }

    function getDescription() {
      return $this->description;
    }

    function getStatus() {
      return $this->status;
    }

    function getCreated_at() {
      return $this->created_at;
    }

    function getUpdated_at() {
      return $this->updated_at;
    }

    function getUser() {
      return $this->user;
    }

    function setId($id) {
      $this->id = $id;
      return $this;
    }

    function setUser_id($user_id) {
      $this->user_id = $user_id;
      return $this;
    }

    function setTitle($title) {
      $this->title = $title;
      return $this;
    }

    function setDescription($description) {
      $this->description = $description;
      return $this;
    }

    function setStatus($status) {
      $this->status = $status;
      return $this;
    }

    function setCreated_at(\DateTime $created_at) {
      $this->created_at = $created_at;
      return $this;
    }

    function setUpdated_at(\DateTime $updated_at) {
      $this->updated_at = $updated_at;
      return $this;
    }

    function setUser(\BackendBundle\Entity\User $user = null) {
      $this->user = $user;
      return $this;
    }

}

