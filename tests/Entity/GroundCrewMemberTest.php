<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Certification;
use App\Entity\GroundCrewMember;
use App\Entity\GroundCrewMemberCertification;
use PHPUnit\Framework\TestCase;

class GroundCrewMemberTest extends TestCase
{
    public function testMarkExpiredCertification(): void
    {
        $c1 = (new GroundCrewMemberCertification())->setCertification((new Certification())->setTitle('C1'))->setExpirationDate(new \DateTime('yesterday'));
        $c2 = (new GroundCrewMemberCertification())->setCertification((new Certification())->setTitle('C2'))->setExpirationDate(new \DateTime('tomorrow'));
        $c3 = (new GroundCrewMemberCertification())->setCertification((new Certification())->setTitle('C3'));
        $member = (new GroundCrewMember())
            ->addGroundCrewMemberCertification($c1)
            ->addGroundCrewMemberCertification($c2)
            ->addGroundCrewMemberCertification($c3)
        ;

        $member->markExpiredCertification();
        $this->assertEquals(true, $c1->isExpired());
        $this->assertEquals(false, $c2->isExpired());
        $this->assertEquals(false, $c3->isExpired());
    }
}
