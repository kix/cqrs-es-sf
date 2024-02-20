<?php

declare(strict_types=1);

namespace Common\UI;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Front controller is responsible for receiving a request, matching it against a command/query dictionary,
 * dispatching it and returning a response.
 *
 * Exceptions should be handled by regular Symfony handlers.
 *
 * @package Facade
 */
final class FrontController
{
    public function __invoke(Request $request): Response
    {
        return new Response(null, Response::HTTP_NOT_FOUND);
    }
}