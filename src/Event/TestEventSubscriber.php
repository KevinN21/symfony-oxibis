<?php
namespace App\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Filesystem\Filesystem;

class TestEventSubscriber implements EventSubscriberInterface
{
  public static function getSubscribedEvents()
  {
    return [
      TestEvent::NAME => 'onTestEvent'
    ];
  }

  public function onTestEvent(TestEvent $event)
  {
    //dump("event listened");
    //dump($event->getMessage());
    $fs = new Filesystem();
    $fs->appendToFile('/tmp/oxibis.log', $event->getMessage() . "\n");
  }
}