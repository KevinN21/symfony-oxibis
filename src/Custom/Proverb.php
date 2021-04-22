<?php

namespace App\Custom;

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


  public function getShortBody($len): string
  {
    return substr($this->body, 0, $len) . '...';
  }
}
