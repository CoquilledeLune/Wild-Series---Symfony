<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
  const SEASONS = [
  ['number'=>'1', 'year'=>1956, 'description'=>'Une saison en enfer', 'program'=>'Queen Gambit'],
  ['number'=>'2', 'year'=>2004, 'description'=>'', 'program'=>'Queen Gambit'],
  ['number'=>'3', 'year'=>2008, 'description'=>'', 'program'=>'Queen Gambit'],
  ['number'=>'1', 'year'=>2020, 'description'=>'Une saison où Dorie meurt', 'program'=>'Dark'],  
  ];

  public function load(ObjectManager $manager): void
  {
    foreach (self::SEASONS as $seasonInformations) 
    {
      $season = new Season();
      $season->setNumber($seasonInformations['number']);
      $season->setYear($seasonInformations['year']);
      $season->setDescription($seasonInformations['description']);
      $season->setProgram(($this->getReference($seasonInformations['program'])));
      $this->addReference($seasonInformations['program'] . $seasonInformations['number'], $season);
      $manager->persist($season);
    }
    $manager->flush();
  }
  public function getDependencies()
  {
    return [
      ProgramFixtures::class
    ];
  }
}