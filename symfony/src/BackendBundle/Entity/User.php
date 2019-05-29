<?php

namespace BackendBundle\Entity;

/**
 * User
 */
class User
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $role;
    
    /**
     * @var string
     */
    private $name;
    
    /**
     * @var string
     */
    private $surname;
    
    /**
     * @var string
     */
    private $email;
    
    /**
     * @var string
     */
    private $password;
    
    /**
     * @var \DateTime
     */
    private $created_at;
    
    function getId() {
      return $this->id;
    }

    function getRole() {
      return $this->role;
    }

    function getName() {
      return $this->name;
    }

    function getSurname() {
      return $this->surname;
    }

    function getEmail() {
      return $this->email;
    }

    function getPassword() {
      return $this->password;
    }

    function getCreated_at() {
      return $this->created_at;
    }

    function setId($id) {
      $this->id = $id;
      return $this;
    }

    function setRole($role) {
      $this->role = $role;
      return $this;
    }

    function setName($name) {
      $this->name = $name;
      return $this;
    }

    function setSurname($surname) {
      $this->surname = $surname;
      return $this;
    }

    function setEmail($email) {
      $this->email = $email;
      return $this;
    }

    function setPassword($password) {
      $this->password = $password;
      return $this;
    }

    function setCreated_at(\DateTime $created_at) {
      $this->created_at = $created_at;
      return $this;
    }
}

