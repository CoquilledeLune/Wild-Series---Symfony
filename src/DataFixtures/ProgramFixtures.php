<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    const PROGRAMS = [
    ['Title'=>'Queen Gambit', 'Synopsis'=>'Une nana super badass aux échecs', 'Category'=>'category_Action'], 
    ['Title'=>'Dark', 'Synopsis'=>'Des gens qui voyagent trop dans le temps', 'Category'=>'category_Fantastique'],
    ['Title'=>'The Simpsons', 'Synopsis'=>'Les aventures d\'une famille americaine', 'Category'=>'category_Animation'],
    ['Title'=>'The O.A.', 'Synopsis'=>'Une femme découvre les secrets de notre univers', 'Category'=>'category_Fantastique'],
    ['Title'=>'Black Mirror', 'Synopsis'=>'Les déroutes de nos écrans', 'Category'=>'category_Fantastique'],
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::PROGRAMS as $informations) {
            $program = new Program();
            $program->setTitle($informations['Title']);
            $program->setSynopsis($informations['Synopsis']);
            $program->setCategory($this->getReference($informations['Category']));
            $manager->persist($program);
    }
            $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
          CategoryFixtures::class,
        ];
    }
}
