<?php

namespace Yiisoft\Yii\Debug\Collector;

use Psr\EventDispatcher\EventDispatcherInterface;

class EventCollector implements CollectorInterface, EventDispatcherInterface
{
    use CollectorTrait;

    private array $events = [];

    private EventDispatcherInterface $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function export(): void
    {
        if ($this->target === null) {
            throw new \RuntimeException('$target can not be null');
        }
        $this->target->add($this->events);
    }

    public function dispatch(object $event)
    {
        $this->collectEvent($event);

        return $this->dispatcher->dispatch($event);
    }

    private function collectEvent(object $event): void
    {
        $this->events[] = [
            'event' => $event,
            'time' => microtime(true),
        ];
    }
}