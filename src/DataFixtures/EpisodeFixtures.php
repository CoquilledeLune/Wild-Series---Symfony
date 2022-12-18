<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface {
const EPISODES = [
  ['number' => 1, 'title' => 'Ouvertures', 'season' => 1, 'programTitle' => 'Queen Gambit'],
  ['number' => 2, 'title' => 'Echanges', 'season' => 1, 'programTitle' => 'Queen Gambit'],
  ['number' => 1, 'title' => 'Finales', 'season' => 1, 'programTitle' => 'Dark'],
];

public function load(ObjectManager $manager) {
  foreach (self::EPISODES as $informations) {
    $episode = new Episode;
    $episode->setNumber($informations['number']);
    $episode->setTitle($informations['title']);
    $episode->setSeason($this->getReference($informations['programTitle'] . $informations['number']));
    $manager->persist($episode);
  }
  $manager->flush();
}
public function getDependencies()
{
  return [
    SeasonFixtures::class
  ];
}
}
