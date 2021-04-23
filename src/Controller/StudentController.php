<?php

namespace App\Controller;

use App\Entity\Student;
use App\Repository\StudentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StudentController extends AbstractController
{
    /**
     * @Route("/student", name="student_index", methods={"GET"})
     */
    public function index(): Response
    {
        // Récupération d'un repository
        // permettant d'interroger (LECTURE) l'entité ciblée
        $repo = $this->getDoctrine()->getRepository(Student::class);
        $students = $repo->findAll();

        return $this->render('student/index.html.twig', [
            'students' => $students
        ]);
    }

    /**
     * @Route("/student/{id}/show", name="student_show", methods={"GET"})
     */
    public function show($id): Response
    {
        // Récupération d'un repository
        // permettant d'interroger (LECTURE) l'entité ciblée
        $repo = $this->getDoctrine()->getRepository(Student::class);
        $student = $repo->find($id);

        if (!$student) {
            return $this->render('student/invalid.html.twig', [
                'errors' => [
                    ['field' => 'id', 'errorMessage' => 'étudiant inconnu']
                ]
            ]);
        }

        return $this->render('student/show.html.twig', [
            'student' => $student
        ]);
    }

    /**
     * @Route("/student/form", name="student_form")
     */
    public function form(): Response
    {
        return $this->render('student/form.html.twig');
    }


    /**
     * @Route("/student", name="student_new", methods={"POST"})
     */
    public function new(Request $req): Response
    {
        // récupération des inputs
        $name = $req->request->get('name');
        $job = $req->request->get('job');
        $age = $req->request->get('age');
        
        // accès à la valeur du champ caché
        // tranmise par formulaire
        $studentId = $req->request->get('studentId');

        // ToDo: valider les inputs
        $errors = [];

        if (strlen($name) < 4) {
            $error = [
                "field" => "name", 
                "errorMessage" => "doit comporter 3 caractères"
            ];
            array_push($errors, $error);
        }

        if ($age > 100 || $age < 6) {
            $error = [
                "field" => "age", 
                "errorMessage" => "doit être compris entre 6 et 100"
            ];
            array_push($errors, $error);
        }

        // A-t-on rencontré au moins une erreur de validation ?
        if (count($errors) > 0) {
            return $this->render('student/invalid.html.twig', [
                'errors' => $errors
            ]);
        }

        // doit-on créer ou modifier un étudiant ?
        if (!$studentId) {
            // création d'un nouvel étudiant
            $student = new Student();
        } else {
            // modification d'un étudiant
            $repo = $this
                ->getDoctrine()
                ->getRepository(student::class);
            
            $student = $repo->find($studentId);
            // ToDo: check $student
        }

        $student->setName($name);
        $student->setJob($job);
        $student->setAge($age);

        $manager = $this->getDoctrine()->getManager();

        $manager->persist($student); // prépare la requête
        $manager->flush(); // exécute la requête

        //return new Response($student->getId());
        return $this->redirectToRoute('student_index');
    }

    /**
     * @Route("/student/{id}/edit", name="student_edit", methods={"GET"})
     */
    public function edit($id, StudentRepository $repo): Response
    {
        $student = $repo->find($id);
        // ToDo: check $student

        return $this->render('student/form.html.twig', [
            'student' => $student
        ]);
    }

    /**
     * @Route("/student/{id}/delete", name="student_delete", methods={"GET"})
     */
    public function delete($id, StudentRepository $repo): Response
    {
        $student = $repo->find($id);
        // ToDo: check $student

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($student);
        $manager->flush();

        return $this->redirectToRoute('student_index');
    }
}
