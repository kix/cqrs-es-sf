<?php

declare(strict_types=1);

namespace Tests\Common\Infrastructure\Transport;

use Common\Infrastructure\Transport\ConfigurationException;
use Common\Infrastructure\Transport\MessageValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;

final class MessageValidatorTest extends TestCase
{
    const string SCHEMAS_ROOT = __DIR__ . '/../../../../schemas';

    private static function createValidator(): MessageValidator
    {
        return new MessageValidator(realpath(self::SCHEMAS_ROOT));
    }

    /**
     * @test
     */
    public function it_throws_when_a_schema_path_does_not_exist(): void
    {
        static::expectException(ConfigurationException::class);

        $validator = new MessageValidator(uniqid('', true));
    }

    /**
     * @test
     * @group functional
     */
    public function it_validates_messages(): void
    {
        $schemas = Finder::create()
            ->files()
            ->in(self::SCHEMAS_ROOT)
            ->contains('/\.schema\.json/i')
            ->getIterator();

        foreach ($schemas as $file) {
            $eventName = substr($file->getFilename(), 0, -12);

            $goodExamples = Finder::create()
                ->files()
                ->in(self::SCHEMAS_ROOT)
                ->contains('/\.good\.json/i')
            ->getIterator();


            foreach ($goodExamples as $example) {
            }

        }

        $validator = self::createValidator();
    }

    public function provideCases(): array
    {

    }
}