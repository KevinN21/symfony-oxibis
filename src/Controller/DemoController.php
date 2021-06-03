<?php
namespace App\Controller;

use App\Custom\Proverb;
use App\Event\TestEvent;
use App\Service\CalculatorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Translation\TranslatableMessage;
use Symfony\Contracts\Translation\TranslatorInterface;

class DemoController extends AbstractController
{
  private $calculatorSrv;

  // public function __construct()
  // {
  //   DI sans auto-wiring
  //   $this->calculatorSrv = new CalculatorService(0.5);
  // }

  public function __construct(CalculatorService $calculatorSrv)
  {
    // DI avec auto-wiring
    $this->calculatorSrv = $calculatorSrv;
  }

  public function demo1()
  {
    //$res = new Response("demo1");
    $res = new Response();

    $title = "demo1";
    $content = $title . " via la méthode setContent";
    $res->setContent($content);

    return $res;
  }

  public function demo2()
  {
    $res = new Response();
    $content = "<h1>Demo2</h1>";
    $content .= "<p>Simple paragraphe</p>";

    $res->setContent($content);
    return $res;
  }

  /**
   * @Route("/demo3", name="demo3")
   */
  public function demo3(Request $req)
  {
    $res = new Response();
    //dd($req); // dump and die
    $student = $req->query->get('student');
    $job = $req->query->get('job');
    $res->setContent("L'étudiant " . $student . " exerce le métier de " . $job);
    return $res;
  }

  /**
   * @Route("/demo4", name="demo4")
   */
  public function demo4(Request $req)
  {
    $res = new Response();
    $correctTokenValue = "abc123";

    $userAgent = $req->headers->get('User-Agent');
    $token = $req->headers->get('X-Token');

    if (!$token) {
      $res->setContent('Token non fourni');
    } else {
      if ($token === $correctTokenValue) {
        $res->setContent('Accès autorisé');
      } else {
        $res->setContent('Token incorrect');
      }
    }

    $res->headers->set('X-Token', md5("hello"));
    $res->setContent('<html><head><title>Demo4</title></head><body></body></html>');
    return $res;
  }

  /**
   * @Route("/demo5", name="demo5")
   */
  public function demo5(Request $req)
  {
    $method = $req->getMethod();

    if (Request::METHOD_GET == $method) {
      return new Response("GET out of here !");
    } else {
      return new Response("Méthode non valide");
    }
  }

  /**
   * @Route("/demo6", name="demo6")
   */
  public function demo6()
  {
    // datasource
    $students = [
      ["name" => "Anthony", "job" => "dev", "age" => 14],
      ["name" => "Kevin", "job" => "dev", "age" => 49],
      ["name" => "Chris", "job" => "dev", "age" => 98]
    ];

    //return new Response(json_encode($students));
    //return new JsonResponse($students); // Content-Type: application/json
    return $this->json($students); // shorthand
  }

  /**
   * @Route("/demo7", name="demo7")
   */
  public function demo7()
  {
    $res = new Response();
    $res->headers->set('Content-Type', 'application/json');
    $res->headers->set('X-Token', md5('la terre est ronde'));
    $res->setStatusCode(Response::HTTP_NOT_FOUND); // 404
    $res->setContent('Ressource trouvée, bravo !');
    return $res;
  }

  /**
   * @Route("/demo8/{student}", name="demo8")
   */
  public function demo8($student)
  {
    if (strlen($student) > 10) {
      return new Response("Nom trop long !");
    }
    return new Response($student);
  }

  /**
   * @Route("/demo9/{num}", 
   *  name="demo9", 
   *  requirements={"num"="\d+"},
   *  methods={"POST"}
   * )
   */
  public function demo9($num)
  {
    return new Response($num * $num);
  }

  /**
   * @Route("/demo10", name="demo10")
   */
  public function demo10(): Response
  {
    return $this->render('demo/demo10.html.twig');
  }

    /**
   * @Route("/demo11", name="demo11")
   */
  public function demo11(): Response
  {
    $title = "Démo 11";

    // datasource
    $students = [
      ["name" => "Anthony", "job" => "dev", "age" => 14],
      ["name" => "Kevin", "job" => "dev", "age" => 49],
      ["name" => "Christophe", "job" => "prof", "age" => 98]
    ];

    return $this->render('demo/demo11.html.twig', [
      'title' => $title,
      'students' => $students
    ]);
  }

  /**
   * @Route("/demo12", name="demo12")
   */
  public function demo12(): Response
  {
    // datasource
    $students = [
      ["name" => "Anthony", "job" => "dev", "age" => 14],
      ["name" => "Kevin", "job" => "dev", "age" => 49],
      ["name" => "Christophe", "job" => "prof", "age" => 98]
    ];

    return $this->render('demo/demo12.html.twig', [
      'students' => $students
    ]);
  }

  /**
   * @Route("/demo13/{studentId}", name="demo13")
   */
  public function demo13($studentId): Response
  {
    // datasource
    $students = [
      ["name" => "Anthony", "job" => "dev", "age" => 14, "img" => "https://picsum.photos/75"],
      ["name" => "Kevin", "job" => "dev", "age" => 49, "img" => "https://picsum.photos/75"],
      ["name" => "Christophe", "job" => "prof", "age" => 98, "img" => "https://picsum.photos/75"]
    ];

    $index = $studentId - 1;

    if ($index > count($students)) {
      return new Response("Requête non valide...");
    }

    return $this->render('demo/demo13.html.twig', [
      'student' => $students[$index]
    ]);
  }

  /**
   * @Route("/demo14", name="demo14")
   */
  public function demo14(): Response
  {
    $p1 = new Proverb("Pierre qui roule n'amasse pas mousse", "fr");
    $p2 = new Proverb("Tra dire e il fare c'è in mezzo il mare", "it");
    $p3 = new Proverb("Ad astra per aspera", "la");

    $proverbs = [$p1, $p2, $p3];

    return $this->render('demo/demo14.html.twig', [
      'proverbs' => $proverbs
    ]);
  }

  /**
   * @Route("/demo15", name="demo15")
   */
  public function demo15(CalculatorService $calculator, Request $request): Response
  {

    $ht = $request->query->get('ht');
    return new Response($calculator->tva($ht));
  }

  /**
   * @Route("/demo16", name="demo16")
   */
  public function demo16(Request $request): Response
  {
    $calculator = new CalculatorService(0.5);
    $ht = $request->query->get('ht');
    return new Response($calculator->tva($ht));
  }

  /**
   * @Route("/demo17", name="demo17")
   */
  public function demo17(Request $request): Response
  {
    $ht = $request->query->get('ht');
    return new Response($this->calculatorSrv->tva($ht));
  }

  /**
   * @Route("/demo18", name="demo18")
   */
  public function demo18(Request $request, EventDispatcherInterface $dispatcher): Response
  {

    $event = new TestEvent("coucou");
    $dispatcher->dispatch($event, TestEvent::NAME);

    return $this->render('demo/demo18.html.twig');
  }

  /**
   * @Route("/demo19", name="demo19")
   */
  public function demo19(Request $request, TranslatorInterface $translator): Response
  {
    //$t = $translator->trans('hello');
    //$t = $translator->trans('state.happy', [], 'demo');
    $t = $translator->trans('say_hello', ['student' => 'Chris']);
    return new Response($t);
  }

  /**
   * @Route("/demo20", name="demo20")
   */
  public function demo20(): Response
  {
    $message = new TranslatableMessage('Students');

    return $this->render('demo/demo20.html.twig', [
      'message' => $message
    ]);
  }

  /**
   * @Route(
   *  "{_locale}/demo21", 
   *  name="demo21", 
   *  requirements={"_locale":"en|fr|it"})
   */
  public function demo21(): Response
  {
    $message = new TranslatableMessage('Students');

    return $this->render('demo/demo20.html.twig', [
      'message' => $message
    ]);
  }

    /**
   * @Route({
   *  "en": "/demo22/happy-new-year",
   *  "fr": "/demo22/joyeuse-annee"
   * }, name="demo22")
   */
  public function demo22(
    Request $request, UrlGeneratorInterface $urlGenerator): Response
  {
    $locale = $request->getLocale();
    $url = $urlGenerator->generate('demo22', ['_locale' => $locale]);
    //dd($url);
    return new Response($url);
  }

}
