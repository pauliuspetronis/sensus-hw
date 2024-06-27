<?php

namespace App\DataFixtures;

use App\Entity\Certification;
use App\Entity\Skill;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        $manager->persist((new Certification())->setTitle('Certification 1'));
        $manager->persist((new Certification())->setTitle('Certification 2'));

        $manager->persist((new Skill())->setName('Skill 1'));
        $manager->persist((new Skill())->setName('Skill 2'));

        $manager->flush();
    }
}
