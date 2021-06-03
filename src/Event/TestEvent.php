<?php
namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class TestEvent extends Event
{
  public const NAME = "app.test";
  private $message;

  public function __construct(string $message)
  {
    $this->message = $message;
  }

  public function getMessage(): string
  {
    return $this->message;
  }
}