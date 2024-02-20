<?php

declare(strict_types=1);

namespace Tests\Architecture;

use PHPat\Selector\ClassNamespace;
use PHPat\Selector\Selector;
use PHPat\Test\Builder\AbstractStep;
use PHPat\Test\PHPat;

/**
 * The layer dependency test employs PHPStan and PHPAT (https://github.com/carlosas/phpat) to validate
 * application architecture rules.
 *
 * TODO: Add a rule that only allows dependencies inside a singular domain?
 * TODO: Check whether /[a-zA-Z]+\\[a-zA-Z]+/ works. Or we can use Selector::AND() for that.
 *
 * @package Quality Control
 */
final class LayerDependencyTest
{
    private static ClassNamespace $applicationLayer;
    private static ClassNamespace $domainLayer;
    private static ClassNamespace $infrastructureLayer;

    public function __construct()
    {
        self::$applicationLayer = Selector::inNamespace('[a-zA-Z]+\\Application\\', true);
        self::$domainLayer = Selector::inNamespace('[a-zA-Z]+\\Domain\\', true);
        self::$infrastructureLayer = Selector::inNamespace('[a-zA-Z]+\\Infrastructure\\', true);
    }

    /**
     * Classes in the application layer cannot depend on anything. Application layer is self-isolated
     * and can be tested completely separately.
     */
    public function application_layer_is_isolated(): AbstractStep
    {
        return PHPat::rule()
            ->classes(self::$applicationLayer)
            ->shouldNotDependOn()
            ->classes(Selector::all());
    }

    public function domain_can_only_depend_on_application(): AbstractStep
    {
        return PHPat::rule()
            ->classes(self::$domainLayer)
            ->canOnlyDependOn()
            ->classes(self::$applicationLayer);
    }

    public function application_can_only_depend_on_infrastructure(): AbstractStep
    {
        return PHPat::rule()
            ->classes(self::$applicationLayer)
            ->canOnlyDependOn()
            ->classes(self::$infrastructureLayer);
    }
}