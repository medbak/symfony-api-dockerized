<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Notebook;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class NotebookFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $notebook = new Notebook();
            $notebook->setIdentifier($faker->unique()->text(20));
            $notebook->setHeadline($faker->text(100));
            $notebook->setContent($faker->text);

            $manager->persist($notebook);
        }

        $manager->flush();
    }
}
