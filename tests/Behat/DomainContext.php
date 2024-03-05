<?php

namespace Tests\Behat;

use Exception;
use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Behat\Tester\Exception\PendingException;
use CqrsEsExample\Event\Application\Command\ApproveEventCommand;
use CqrsEsExample\Event\Application\Command\RegisterEventCalendarCommand;
use CqrsEsExample\Event\Application\Command\SubmitEventCommand;
use CqrsEsExample\Event\Application\Query\ListEventsQuery;
use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class DomainContext implements Context
{
    private string $role = 'ROLE_USER';

    public function __construct(
        private readonly MessageBusInterface $commandBus,
        private readonly MessageBusInterface $queryBus,
        private readonly Connection $dbalConnection,
    ) { }

    /**
     * @BeforeScenario
     */
    public function clearEvents(BeforeScenarioScope $scope): void
    {
        $this->dbalConnection->executeQuery('DELETE FROM domain_events');
        $this->dbalConnection->executeQuery('DELETE FROM events');
    }

    /**
     * @Given I am an anonymous user
     */
    public function iAmAnAnonymousUser(): void
    {

    }

    /**
     * @Given I am an administrator
     */
    public function iAmAnAdministrator(): void
    {
        $this->role = 'ROLE_ADMIN';

        // Set this in security context, and then a command handler or an event listener can decide whether
        // to submit an `ApproveEventCommand` straight after?
        // Or should the command handler decide?
    }

    /**
     * @Given there is an event calendar
     */
    public function thereIsAnEventCalendar(): void
    {
        $this->commandBus->dispatch(new RegisterEventCalendarCommand());
    }

    /**
     * @When I submit an event named :title happening on :dateTimeStart ending on :dateTimeEnd in :locationName
     */
    public function iSubmitAnEvent(string $title, string $dateTimeStart, string $dateTimeEnd, string $locationName): void
    {
        $this->commandBus->dispatch(new SubmitEventCommand(
            $title,
            new DateTimeImmutable($dateTimeStart),
            new DateTimeImmutable($dateTimeEnd),
            $locationName
        ));
    }

    /**
     * @When the event named :arg1 happening on :arg2 ending on :dateTimeEnd in :arg3 is approved by an admin
     */
    public function theEventIsApprovedByAnAdmin(string $title, string $dateTimeStart, string $dateTimeEnd, string $locationName): void
    {
        $this->commandBus->dispatch(new ApproveEventCommand(
            $title,
            new DateTimeImmutable($dateTimeStart),
            new DateTimeImmutable($dateTimeEnd),
            $locationName
        ));
    }

    /**
     * @Then I do not see any events in the event list
     */
    public function iDontSeeAnyEventsInTheEventList(): void
    {
        $events = $this->dispatchQuery(new ListEventsQuery());

        if (count($events) > 0) {
            throw new Exception('Expected no events, but found some');
        }
    }

    /**
     * @Then I see the event named :name happening on :startDate in :location in the event list
     */
    public function iSeeTheEventNamedHappeningOnInInTheEventList(string $title, string $startDate, string $location): void
    {
        $events = $this->dispatchQuery(new ListEventsQuery());

        foreach ($events as $event) {
            if ($event['title'] === $title && $event['location'] === $location) {
                return;
            }
        }

        throw new \Exception('Event not found');
    }

    /**
     * @Then I should get an invariant violation
     */
    public function iShouldGetAnInvariantViolation(): void
    {
        throw new PendingException();
    }

    private function dispatchQuery(object $query): array
    {
        $envelope = $this->queryBus->dispatch($query);

        return $envelope->last(HandledStamp::class)->getResult();
    }
}
