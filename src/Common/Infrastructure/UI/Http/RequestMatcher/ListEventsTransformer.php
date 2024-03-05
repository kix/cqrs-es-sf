<?php

declare(strict_types=1);

namespace CqrsEsExample\Common\Infrastructure\UI\Http\RequestMatcher;

use CqrsEsExample\Event\Application\Query\ListEventsQuery;
use Override;
use Symfony\Component\HttpFoundation\Request;

final class ListEventsTransformer implements RequestTransformerInterface
{
    #[Override]
    public function supports(Request $request): bool
    {
        return $request->getRequestUri() === '/events';
    }

    #[Override]
    public function transform(Request $request): object
    {
        return new ListEventsQuery();
    }
}