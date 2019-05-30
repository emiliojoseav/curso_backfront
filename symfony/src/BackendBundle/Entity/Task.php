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
    private $createdAt;
    
    /**
     * @var \DateTime
     */
    private $updatedAt;
    
    /**
     * @var \BackendBundle\Entity\User
     */
    private $user;

    function getId() {
      return $this->id;
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

    function getCreatedAt() {
      return $this->createdAt;
    }

    function getUpdatedAt() {
      return $this->updatedAt;
    }

    function getUser() {
      return $this->user;
    }

    function setId($id) {
      $this->id = $id;
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

    function setCreatedAt(\DateTime $createdAt) {
      $this->createdAt = $createdAt;
      return $this;
    }

    function setUpdatedAt(\DateTime $updatedAt) {
      $this->updatedAt = $updatedAt;
      return $this;
    }

    function setUser(\BackendBundle\Entity\User $user) {
      $this->user = $user;
      return $this;
    }
}

