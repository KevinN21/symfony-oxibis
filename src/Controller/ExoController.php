<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExoController extends AbstractController
{
  /**
   * @Route("exos/exo1/{num}", name="exo1")
   */
  public function exo1($num)
  {
    if (is_numeric($num)) {
      return new Response($num * $num);
    } else {
      return new Response($num . " n'est pas calculable...");
    }
    
  }
}