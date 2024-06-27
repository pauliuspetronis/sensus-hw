<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\GroundCrewMember;
use App\Entity\Skill;
use App\Entity\Task;
use App\Repository\GroundCrewMemberRepository;
use App\Service\GroundCrewMemberProvider;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class GroundCrewMemberProviderTest extends TestCase
{
    /**
     * @dataProvider provideMembers
     */
    public function testGetByTask(array $members, array $expected, Task $task): void
    {
        $repository = $this->createMock(GroundCrewMemberRepository::class);
        $repository->method('findBySkills')->willReturn($members);

        $provider = new GroundCrewMemberProvider($repository);

        $this->assertEquals($expected, $provider->getByTask($task));
    }

    public function provideMembers(): \Generator
    {
        yield 'Empty members' => [
            'members' => [],
            'expected' => [],
            'task' => $this->createMock(Task::class),
        ];

        $task = $this->createMock(Task::class);
        $requiredSkills = [
            (new Skill())->setName('Skill 1'),
            (new Skill())->setName('Skill 2'),
        ];
        $task->method('getRequiredSkills')->willReturn(new ArrayCollection($requiredSkills));

        $members = [
            $this->createMock(GroundCrewMember::class),
        ];
        yield 'One member without skills' => [
            'members' => $members,
            'expected' => [],
            'task' => $task,
        ];

        $member1 = (new GroundCrewMember())->addSkills(new ArrayCollection($requiredSkills));
        $members = [$member1];
        yield 'One member with all required skills' => [
            'members' => $members,
            'expected' => $members,
            'task' => $task,
        ];

        $member1 = (new GroundCrewMember())->addSkills(new ArrayCollection([$requiredSkills[0]]));
        $members = [$member1];
        yield 'One member with one required skill' => [
            'members' => $members,
            'expected' => [],
            'task' => $task,
        ];

        $member1 = (new GroundCrewMember())->addSkills(new ArrayCollection([$requiredSkills[0]]));
        $member2 = (new GroundCrewMember())->addSkills(new ArrayCollection($requiredSkills));
        $members = [$member1, $member2];
        yield 'Two member one of them has all required skill' => [
            'members' => $members,
            'expected' => [$member2],
            'task' => $task,
        ];
    }
}
