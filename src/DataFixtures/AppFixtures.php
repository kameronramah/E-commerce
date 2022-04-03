<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $lifeStyle = new Category();
        $running = new Category();
        $basketball = new Category();
        
        $lifeStyle->setName('Lifestyle');
        $running->setName('Running');
        $basketball->setName('Basketball');

        $manager->persist($lifeStyle);
        $manager->persist($running);
        $manager->persist($basketball);

        $lifeStyle1 = new Product();
        $lifeStyle2 = new Product();
        $lifeStyle3 = new Product();

        $lifeStyle1->setQuantityProduct(100);
        $lifeStyle1->setPrice(139.99);
        $lifeStyle1->setImage('jordan_legacy_312_low.jpg');
        $lifeStyle1->setName('Air Jordan Legacy 312 Low');
        $lifeStyle1->setDescription('La Air Jordan Legacy 312 Low rend hommage au parcours de Michael Jordan, en arborant le code postal 312 de Chicago. Ce modèle associe à sa façon tous les éléments emblématiques de Jordan.');
        $lifeStyle1->addCategory($lifeStyle);

        $lifeStyle2->setQuantityProduct(100);
        $lifeStyle2->setPrice(119.99);
        $lifeStyle2->setImage('huarache.png');
        $lifeStyle2->setName('Nike Air Huarache');
        $lifeStyle2->setDescription('Conçue pour s\'adapter à votre pied et assurer un confort optimal, la Nike Air Huarache signe le retour d\'un incontournable du style urbain.Les détails en cuir souple sur l\'empeigne sont associés à un tissu de type néoprène parfaitement brillant et ultra-respirant pour un style polyvalent.Le col bas et la conception de type chausson assurent élégance et confort.Le clip emblématique au talon et le logo épuré conservent le look du début des années 90 que vous aimez tant.');
        $lifeStyle2->addCategory($lifeStyle);

        $lifeStyle3->setQuantityProduct(100);
        $lifeStyle3->setPrice(109.99);
        $lifeStyle3->setImage('air_force.png');
        $lifeStyle3->setName('Nike Air Force 1 \'07');
        $lifeStyle3->setDescription('Le charme continue d\'opérer avec la Nike Air Force 1 ’07. Cette silhouette emblématique du basketball revisite ses éléments les plus célèbres : les renforts cousus résistants, les finitions sobres et juste ce qu\'il faut d\'éclat pour briller sur le terrain.');
        $lifeStyle3->addCategory($lifeStyle);

        $manager->persist($lifeStyle1);
        $manager->persist($lifeStyle2);
        $manager->persist($lifeStyle3);

        $running1 = new Product();
        $running2 = new Product();
        $running3 = new Product();

        $running1->setQuantityProduct(100);
        $running1->setPrice(119.99);
        $running1->setImage('pegasus_38.jpg');
        $running1->setName('Nike Air Zoom Pegasus 38');
        $running1->setDescription('La route est votre terrain de jeu. Préparez-vous à prendre votre envol avec la chaussure ailée ultra-performante. De retour avec un rebond parfait pour fouler le bitume. Que vous fassiez votre run quotidien ou vous prépariez pour un run plus long, ressentez le rebond à chaque foulée avec le même amorti que le modèle précédent. L\'empeigne en mesh respirant vous offre le confort et la résistance dont vous avez besoin, avec une coupe plus large au niveau des orteils.');
        $running1->addCategory($running);

        $running2->setQuantityProduct(100);
        $running2->setPrice(64.99);
        $running2->setImage('flex_experience_run_10.jpg');
        $running2->setName('Nike Flex Experience Run 10');
        $running2->setDescription('Simple et polyvalente, la Nike Flex Experience Run 10 est conçue pour bouger.Conçue pour les coureurs occasionnels, sa coupe sécurisée et son amorti léger au niveau du talon vous permettent d\'avaler les kilomètres.De plus, l\'empeigne simple revisitée s\'associe à merveille à une tenue décontractée pour vous offrir un confort optimal tout au long de la journée.');
        $running2->addCategory($running);

        $running3->setQuantityProduct(100);
        $running3->setPrice(10);
        $running3->setImage('moss.jpeg');
        $running3->setName('Nike Air Zoom Pegasus 38 A.I.R. Jordan Moss');
        $running3->setDescription('La chaussure ailée ultra-performante est de retour. La Nike Air Zoom Pegasus 38 apporte toujours plus de rebond à votre foulée grâce à une mousse ultra-réactive. L\'empeigne en mesh respirant vous offre confort et résistance, et présente un design plus large au niveau de la pointe. Conçue pour les runs quotidiens, courts ou longs, cette chaussure offre un maintien et un amorti exceptionnels qui vous permettront d\'établir un nouveau record personnel. Les images audacieuses à motifs fleuris de l\'artiste Jordan Moss célèbrent l\'art du mouvement.');
        $running3->addCategory($running);

        $manager->persist($running1);
        $manager->persist($running2);
        $manager->persist($running3);
        
        $basketball1 = new Product();
        $basketball2 = new Product();
        $basketball3 = new Product();

        $basketball1->setQuantityProduct(100);
        $basketball1->setPrice(99.99);
        $basketball1->setImage('jordan-one-take-3.jpg');
        $basketball1->setName('Jordan One Take 3');
        $basketball1->setDescription('Enflammez le parquet comme Russell Westbrook. Inspirée de sa toute dernière chaussure de match, la semelle extérieure de la Jordan One Take 3 vous permet de changer de direction ou de vous arrêter d\'un coup facilement.Accélérez le tempo comme Russ grâce à un amorti Zoom Air à retour d\'énergie qui vous permet d\'effectuer des tirs incroyables et de prendre le parquet d\'assaut encore et encore.');
        $basketball1->addCategory($basketball);

        $basketball2->setQuantityProduct(100);
        $basketball2->setPrice(179.99);
        $basketball2->setImage('kd_14.jpg');
        $basketball2->setName('KD14 By You');
        $basketball2->setDescription('La KD14 By You est conçue pour vous aider à rester au frais, polyvalent et implacable, tout comme KD.Perfectionnez votre look avec des coloris unis ou bicolores, des détails éclatants comme les Swoosh chromés et un strap irisé, sans oublier le plus grand espace pour votre message personnel jamais créé par Nike. .');
        $basketball2->addCategory($basketball);

        $basketball3->setQuantityProduct(100);
        $basketball3->setPrice(184.99);
        $basketball3->setImage('jordan_global_game.jpg');
        $basketball3->setName('Air Jordan XXXVI SE Kia « Global Game »');
        $basketball3->setDescription('Avant ses débuts professionnels en 2018, Kia a conquis le monde avec son pays d\'origine depuis l\'âge de 16 ans.Après avoir fait son show, mené son pays vers la victoire en quelques minutes et marqué sur la scène mondiale en 2016, Kia sait qu\'elle n\'est pas là pour rien.Inspirée par tous les endroits où Kia peut jouer au basketball, la Air Jordan XXXVI « Global Game » célèbre la détermination et la fierté qui accompagne le fait de représenter les couleurs de son pays.');
        $basketball3->addCategory($basketball);

        $manager->persist($basketball1);
        $manager->persist($basketball2);
        $manager->persist($basketball3);

        $manager->flush();
    }
}
