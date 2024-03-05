<?php

declare(strict_types=1);

namespace CqrsEsExample\Common\Infrastructure\UI\Http;

use RuntimeException;
use CqrsEsExample\Common\Infrastructure\UI\Http\RequestMatcher\RequestTransformerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Messenger\Stamp\TransportNamesStamp;

final readonly class KernelRequestListener
{
    public function __construct(
        /**
         * @var iterable<RequestTransformerInterface>
         */
        private iterable $transformers,
        private MessageBusInterface $commandBus,
        private MessageBusInterface $queryBus,
    ) {}

    public function __invoke(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $transformer = $this->getTransformer($request);

        if ($transformer) {
            $message = $transformer->transform($request);

            if (str_ends_with('Command', $message::class)) {
                // Return ACK immediately
                $this->commandBus->dispatch($message, [
                    new TransportNamesStamp(['async'])
                ]);
                $event->setResponse(new Response('', Response::HTTP_ACCEPTED));
            }

            if (str_ends_with('Query', $message::class)) {
                $envelope = $this->queryBus->dispatch($message);
                $handledStamp = $envelope->last(HandledStamp::class);

                if (!$handledStamp) {
                    throw new RuntimeException('Query handler did not return anything');
                }

                assert($handledStamp instanceof HandledStamp);
                $event->setResponse(new JsonResponse($handledStamp->getResult()));
            }
        }
    }

    private function getTransformer(Request $request): RequestTransformerInterface|null
    {
        foreach ($this->transformers as $transformer) {
            if ($transformer->supports($request)) {
                return $transformer;
            }
        }

        return null;
    }
}