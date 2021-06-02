<?php

namespace App\Controller;

use App\Entity\Team;
use App\Repository\TeamRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function new(): Response
    {
        $dt = new \DateTime(); // renvoie le datetime courant
        $dt->setDate(2018, 6, 15);

        $team = new Team();
        $team->setName("Les Barjots");
        $team->setYear($dt);

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($team);
        $manager->flush();

        return new Response($team->getId());
    }

    public function allTeams(TeamRepository $repo): Response
    {
        // ToDo: retourne un tableau de Team à destination
        // de templates twig...
        //return new Response('allTeams');

        return $this->render('team/_teams.html.twig', [
            'teams' => ['aaa', 'bbb', 'ccc']
        ]);
    }
}
