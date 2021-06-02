<?php

namespace App\Controller;

use App\Entity\Team;
use App\Repository\TeamRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeamController extends AbstractController
{
    /**
     * @Route("/team", name="team_index")
     */
    public function index(TeamRepository $repo): Response
    {
        return $this->render('team/index.html.twig', [
            'teams' => $repo->findAll()
        ]);
    }

    /**
     * @Route("/team/new", name="team_new")
     */
    public function new(Request $request): Response
    {
        // $dt = new \DateTime(); // renvoie le datetime courant
        // $dt->setDate(2021, 5, 10);

        // $team = new Team();
        // $team->setName("Les 4 fantastiques");
        // $team->setYear($dt);

        // $manager = $this->getDoctrine()->getManager();
        // $manager->persist($team);
        // $manager->flush();

        // return new Response($team->getId());

        $team = new Team();

        $form = $this->createFormBuilder($team)
            ->add('name', TextType::class, ['label' => 'Nom'])
            ->add('year', TextType::class, ['label' => 'Année'])
            ->add('save', SubmitType::class, ['label' => 'Enregistrer'])
            ->getForm();

        // mise en relation de la requête et du formulaire

        return $this->render('team/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function allTeams(TeamRepository $repo): Response
    {
        // ToDo: retourne un tableau de Team à destination
        // de templates twig...
        //return new Response('allTeams');

        $teams = $repo->findAll();

        return $this->render('team/_teams.html.twig', [
            'teams' => $teams
        ]);
    }
}
