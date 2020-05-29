<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Entity\User;
use App\Entity\BienImmo;
use DateTime;

use Faker;

class UserFixtures extends Fixture
{
    private $encoder;

    /**
     * Utilisation du constructeur pour récupérer le service de hashage des mots de passe via autowiring
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $em)
    {
        $faker = Faker\Factory::create('fr_FR');

        // Création compte admin
        $admin = new User();
        $admin
            ->setEmail('a@a.a')
            ->setRoles(["ROLE_ADMIN"])
            ->setPassword($this->encoder->encodePassword($admin, 'Azerty741.'))
            ->setFirstname('patricl')
            ->setLastname('rené')
            ->setPseudonym('Batmoule')        
            ->setActivated(true)    // Compte activé
            ->setActivationToken(md5( random_bytes(100) ))
        ;
        $em->persist($admin);
        $em->flush();
    }
}
