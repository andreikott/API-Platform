<?php declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\Status;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class StatusPersister implements EventSubscriber
{
    /**
     * @var string
     */
    private $class;

    /**
     * StatusPersister constructor.
     *
     * @param string $class
     *
     * @throws \Exception
     */
    public function __construct(string $class)
    {
        if (!class_exists($class)) {
            throw new \Exception("Class $class not found.");
        }

        $this->class = $class;
    }

    public function getSubscribedEvents()
    {
        return [
            'prePersist',
        ];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $object = $args->getObject();
        if ($object instanceof $this->class) {
            $repo = $args->getObjectManager();
            $status = $repo->getRepository(Status::class)->find(Status::STATUS_NEW);

            /* @noinspection PhpUndefinedMethodInspection */
            $object->setStatus($status);
        }
    }
}
