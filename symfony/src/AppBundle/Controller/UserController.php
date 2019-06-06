<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\User;

/**
 * Description of UserController
 *
 * @author Emilio.Amado
 */
class UserController extends Controller {

  public function newUserAction(Request $request) {
    echo "Hola mundo newAction";
    die();
  }

}
