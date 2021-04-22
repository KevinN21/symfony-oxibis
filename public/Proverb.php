<?php

class Proverb
{
  //propriétés
  private $body;
  private $lang;


  //méthodes
  // constructeur
  public function __construct(
    string $body = '', 
    string $lang = '')
  {
    // hydratation
    $this->body = $body;
    $this->lang = $lang;
  }


  // getters
  public function getBody(): string
  {
    return $this->body;
  }

  public function getLang(): string
  {
    return $this->lang;
  }


  // setters
  public function setBody(string $body)
  {
    $this->body = $body;
  }

  public function setLang(string $lang)
  {
    $this->lang = $lang;
  }
}


//Proverbe modélisé par tableau associatif
// $proverb = [
//   "body" => "Pierre qui roule...",
//   "lang" => "fr",
//   "topic" => "Amour"
// ];
// var_dump($proverb);

$proverb1 = new Proverb();
//$proverb1->body = "Pierre qui roule...";
//$proverb1->lang = "fr";
$proverb1->setBody("Pierre qui roule...");
$proverb1->setLang("fr");

echo $proverb1->getBody();
echo $proverb1->getLang();

$proverb2 = new Proverb("Tra il dire e il fare c'è in mezzo il mare ", "it");
echo '<br><br>';

echo $proverb2->getBody();
echo $proverb2->getLang();
