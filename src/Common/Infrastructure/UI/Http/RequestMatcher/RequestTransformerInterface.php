<?php

declare(strict_types=1);

namespace CqrsEsExample\Common\Infrastructure\UI\Http\RequestMatcher;

use Symfony\Component\HttpFoundation\Request;

interface RequestTransformerInterface
{
    public function supports(Request $request): bool;

    public function transform(Request $request): object;
}