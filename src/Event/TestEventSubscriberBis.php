<?php
namespace App\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TestEventSubscriberBis implements EventSubscriberInterface
{
  public static function getSubscribedEvents()
  {
    return [
      TestEvent::NAME => 'onTestEvent',
      'kernel.response' => ['onKernelResponse', 100]
    ];
  }

  public function onTestEvent(TestEvent $event)
  {
    dump($event->getMessage());
  }

  public function onKernelResponse($event)
  {
    $event->getResponse()->headers->set('X-Formation', 'Symfony');
  }
}