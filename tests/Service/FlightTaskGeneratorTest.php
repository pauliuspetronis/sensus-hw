<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\Flight;
use App\Entity\GroundCrewMember;
use App\Entity\GroundCrewMemberTask;
use App\Entity\Skill;
use App\Entity\Task;
use App\Service\FlightTaskGenerator;
use App\Service\GroundCrewMemberProvider;
use App\Service\GroundCrewMemberTaskManager;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class FlightTaskGeneratorTest extends TestCase
{
    private GroundCrewMemberProvider $groundCrewMemberProvider;

    private GroundCrewMemberTaskManager $groundCrewMemberTaskManager;

    private FlightTaskGenerator $flightTaskGenerator;

    public function provideFlightData(): \Generator
    {
        // Test case 0
        yield 'Without tasks' => [
            'flight' => new Flight(),
            'members' => [],
            'expected' => [],
        ];


        // Test case 1
        $requiredSkills = [
            (new Skill())->setName('Skill 1'),
            (new Skill())->setName('Skill 2'),
        ];
        $members = [
            (new GroundCrewMember())->setFullName('Member 1')->addSkills(new ArrayCollection($requiredSkills)),
        ];
        $task = new Task();
        $task->setTitle('Task 1');
        yield 'One task & one member' => [
            'flight' => (new Flight())
                ->addTask(
                    $task
                        ->setRequiredSkills(new ArrayCollection($requiredSkills)),
                ),
            'members' => $members,
            'expected' => [
                (new GroundCrewMemberTask())->setGroundCrewMember($members[0])->setTask($task),
            ],
        ];

        // Test case 2
        $members = [
            (new GroundCrewMember())->setFullName('Member 1')->addSkills(new ArrayCollection($requiredSkills)),
            (new GroundCrewMember())->setFullName('Member 2')->addSkills(new ArrayCollection($requiredSkills)),
        ];
        $task = new Task();
        $task->setTitle('Task 1');
        yield 'One task & two members' => [
            'flight' => (new Flight())->addTask($task->setRequiredSkills(new ArrayCollection($requiredSkills))),
            'members' => $members,
            'expected' => [
                (new GroundCrewMemberTask())->setGroundCrewMember($members[0])->setTask($task),
            ],
        ];

        // Test case 3
        $member1 = $this->createMock(GroundCrewMember::class);
        $member1->method('getId')->willReturn(1);
        $member1->method('getFullName')->willReturn('Member 1');
        $member1->method('getSkills')->willReturn(new ArrayCollection($requiredSkills));

        $member2 = $this->createMock(GroundCrewMember::class);
        $member2->method('getId')->willReturn(2);
        $member2->method('getFullName')->willReturn('Member 2');
        $member2->method('getSkills')->willReturn(new ArrayCollection($requiredSkills));
        $members = [$member1, $member2];
        $tasks = [
            (new Task())->setTitle('Task 1')->setRequiredSkills(new ArrayCollection($requiredSkills)),
            (new Task())->setTitle('Task 2')->setRequiredSkills(new ArrayCollection($requiredSkills)),
        ];
        yield 'Two task & two members' => [
            'flight' => (new Flight())->addTasks(new ArrayCollection($tasks)),
            'members' => $members,
            'expected' => [
                (new GroundCrewMemberTask())->setGroundCrewMember($members[0])->setTask($tasks[0]),
                (new GroundCrewMemberTask())->setGroundCrewMember($members[1])->setTask($tasks[1]),
            ],
        ];
    }

    protected function setUp(): void
    {
        $this->groundCrewMemberProvider = $this->createMock(GroundCrewMemberProvider::class);
        $this->groundCrewMemberTaskManager = $this->createMock(GroundCrewMemberTaskManager::class);
        $this->flightTaskGenerator = new FlightTaskGenerator(
            $this->groundCrewMemberProvider,
            $this->groundCrewMemberTaskManager,
        );
    }

    /**
     * @dataProvider provideFlightData
     */
    public function testGenerate(Flight $flight, array $members, array $expected): void
    {
        $this->groundCrewMemberProvider->method('getByTask')->willReturn($members);

        $matcher = $this->exactly(sizeof($expected));
        $this->groundCrewMemberTaskManager->expects($matcher)
            ->method('register')
            ->with(
                $this->callback(
                    function (GroundCrewMemberTask $task) use ($expected, $matcher) {
                        $this->assertEquals($task, $expected[$matcher->getInvocationCount() - 1]);

                        return true;
                    },
                ),
            );

        $this->flightTaskGenerator->generate($flight);
    }
}
