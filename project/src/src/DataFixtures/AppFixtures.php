<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $test = new JustTest();
        // $test->setName('привет петушина');
        // $manager->persist($test);

        // $manager->flush();
    }
}
