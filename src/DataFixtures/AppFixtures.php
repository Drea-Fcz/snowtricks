<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use App\Entity\TrickGroup;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('en_EN');

        $groupList = ['Grabs', 'Rotations', 'Flips', 'Old School', 'Rotations Désaxées'];
        $groups = [];
        for ($i = 0; $i < 5; $i++){
            $trickGroup = new TrickGroup();
            $trickGroup->setName($faker->randomElement($groupList))
                ->setDescription($faker->sentence());

            $groups[] = $trickGroup;
            $manager->persist($trickGroup);
        }

        for ($i = 0; $i < 10; $i++){
            $trick = new Trick();

            $trick->setName('Trick' . $i)
                ->setDescription($faker->sentence())
                ->setSlug('Slug' . $i)
                ->setTripGroup($faker->randomElement($groups));

            $manager->persist($trick);
        }


        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
