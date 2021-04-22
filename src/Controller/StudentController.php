<?php

namespace App\Controller;

use App\Entity\Student;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StudentController extends AbstractController
{

    /**
     * @Route("/student/form", name="form_student")
     */
    public function form(): Response
    {
        return $this->render('student/form.html.twig');
    }


    /**
     * @Route("/student", name="new_student")
     */
    public function new(): Response
    {
        $student = new Student();
        $student->setName('Kevin');
        $student->setJob('dev');
        $student->setAge(14);

        $manager = $this->getDoctrine()->getManager();

        $manager->persist($student); // prépare la requête
        $manager->flush(); // exécute la requête

        return new Response($student->getId());
    }
}
