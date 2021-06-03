<?php

namespace App\Controller;

use App\Entity\Student;
use App\Entity\Team;
use App\Form\StudentType;
use App\Repository\StudentRepository;
use App\Repository\TeamRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class StudentController extends AbstractController
{
    /**
     * @Route("/student", name="student_index", methods={"GET"})
     */
    public function index(Request $req): Response
    {
        // Récupération d'un repository
        // permettant d'interroger (LECTURE) l'entité ciblée
        $repo = $this->getDoctrine()->getRepository(Student::class);
        //$students = $repo->findAll();

        $job = $req->query->get('job');
        $name = $req->query->get('name');

        $criteria = [];

        if ($job && $job !== 'all') {
            $criteria = ['job' => $job];
        }

        $students = $repo->findBy($criteria, ['name' => 'ASC']);
        
        // Exemples de finders personnalisés
        //$students = $repo->findAllBis();
        //$students = $repo->findByTeam('Experts');
        //$students = $repo->findMajors();
        //$students = $repo->findInName('o');

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
     * @Route("/student", name="student_new_v1", methods={"POST"})
     */
    public function new_v1(Request $req): Response
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

            // mise à jour de l'équipe
            $teamId = $req->request->get('teamId');
            $teamRepo = $this->getDoctrine()->getRepository(Team::class);
            $student->setTeam($teamRepo->find($teamId));
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
    public function edit($id, 
        StudentRepository $studentRepo,
        TeamRepository $teamRepo): Response
    {
        $student = $studentRepo->find($id);
        // ToDo: check $student

        $teams = $teamRepo->findAll();

        return $this->render('student/form.html.twig', [
            'student' => $student,
            'teams' => $teams
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

    /**
     * @Route("/student/new", name="student_new")
     */
    public function new(Request $request, ValidatorInterface $validator)
    {
        $student = new Student();

        // demo validator service
        // $student->setAge(600);
        // $errors = $validator->validate($student);
        // dd($errors); // violations: array:1


        $form = $this->createForm(StudentType::class, $student);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($student);
            $em->flush();
            //return $this->redirectToRoute('student_index');
        }

        return $this->render('student/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
