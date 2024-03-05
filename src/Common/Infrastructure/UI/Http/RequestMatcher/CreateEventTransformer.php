<?php

declare(strict_types=1);

namespace CqrsEsExample\Common\Infrastructure\UI\Http\RequestMatcher;

use DateTimeImmutable;
use CqrsEsExample\Event\Application\Command\SubmitEventCommand;
use Override;
use Symfony\Component\HttpFoundation\Request;

final readonly class CreateEventTransformer implements RequestTransformerInterface
{
    #[Override]
    public function supports(Request $request): bool
    {
        return $request->getRequestUri() === '/events/create';
    }

    #[Override]
    public function transform(Request $request): object
    {
        return new SubmitEventCommand(
            (string) $request->request->get('name'),
            new DateTimeImmutable(
                (string) $request->request->get('start')
            ),
            new DateTimeImmutable(
                (string) $request->request->get('end')
            ),
            (string) $request->request->get('location')
        );
    }
}