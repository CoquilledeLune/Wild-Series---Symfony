<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface {
const EPISODES = [
  ['number' => 1, 'title' => 'Ouvertures','synopsis' => 'À la suite du décès d\'Alice, sa mère, dans un accident
   de voiture, la petite Elizabeth "Beth" Harmon, âgée de 9 ans, arrive dans un orphelinat. Un jour, une professeur 
   lui demande d\'aller nettoyer les tampons du tableau dans le sous-sol. Là, elle découvre Monsieur Shaibel, le 
   concierge de l\'orphelinat, qui joue tout seul aux échecs. Tout de suite intéressée par le jeu, elle lui demande 
   si elle peut jouer avec lui, mais il refuse catégoriquement, prétextant que les échecs ne sont pas pour les femmes.', 
   'season' => 1, 'programTitle' => 'Queen Gambit'],
  ['number' => 2, 'title' => 'Echanges', 'synopsis' => 'Six ans plus tard, Beth est adoptée par la famille Wheatley. 
  Allston Wheatley, le mari, est souvent absent pour son travail, et Alma, l\'épouse, est mère au foyer. Beth découvre
   sa nouvelle chambre. Le lendemain, alors qu\'elle a du mal à s\'intégrer dans son nouveau lycée, principalement auprès 
   d\'un groupe de filles dont Margaret, la leader populaire, elle cherche un club d\'échecs, en vain.', 'season' => 1, 
   'programTitle' => 'Queen Gambit'],
  ['number' => 1, 'title' => 'Secrets (Geheimnisse)', 'synopsis' => 'En juin 2019, Michael Kahnwald, 43 ans, se suicide, 
  mais sa mère Ines cache sa lettre de suicide avant que quiconque ne la remarque. Le 4 novembre, après près de deux mois 
  de traitement dans un établissement psychiatrique, Jonas, le fils adolescent de Michael, retourne à l\'école et retrouve
   son meilleur ami Bartosz Tiedemann, qui sort maintenant avec Martha. Erik Obendorf, le principal fournisseur de 
   marijuana du lycée, a disparu depuis deux semaines. L\'officier de police Ulrich Nielsen - père de Martha et de ses 
   frères, le jeune Magnus et le petit Mikkel - est chargé de l\'enquête et peine à découvrir des indices.', 
   'season' => 1, 'programTitle' => 'Dark'],
];

public function load(ObjectManager $manager) {
  foreach (self::EPISODES as $informations) {
    $episode = new Episode;
    $episode->setNumber($informations['number']);
    $episode->setTitle($informations['title']);
    $episode->setSynopsis($informations['synopsis']);
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
