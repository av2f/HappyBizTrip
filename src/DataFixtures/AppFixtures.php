<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder){
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
    * Generate randomly a pseudo
    * 
    * Author    : F. Parmentier
    * Created   : 2019/07/29
    *
    * @param string $firstName
    * @return string
    */
    private function createPseudo(string $firstName): string {
        $number=(string)mt_rand(120,999);
        $firstN=(strlen($firstName)>5) ? substr($firstName,0,strlen($firstName)-3) : $firstName;
        $pseudo=mb_strtolower($firstN).$number;
        $search=array("/é/","/è/","/ê/","/ë/");
        $pseudo = preg_replace($search,'e',$pseudo);
        return $pseudo;
    }
    
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        // Define Genders (W=Woman / M=Man)
        $genders=array('W', 'M');
        // Define Situations (C=Couple / S=Single / W=Widow)
        $situations=array('C', 'S');
        for ($i=0; $i<20; $i++){

            $user = new User();

            // generate randomly situation
            $situation=$situations[mt_rand(0,count($situations)-1)];

            // generate randomly the gender
            $gender=$genders[mt_rand(0, count($genders)-1)];

                // Generate firstname and avatar following the gender
            $firstName = ($gender=='M' ? $faker->firstNameMale : $faker->firstNameFemale);

            // Generate a picture
            $picture='https://randomuser.me/api/portraits/';
            $pictureId=$faker->numberbetween(1,99) . '.jpg';
            $picture .= ($gender=='M' ? 'men/' : 'women/') .$pictureId;
            
            // define a birthday date
            $birthDate= new \DateTime();
            $birthDate=$faker->dateTimeBetween('-60 years','-20 years');

            // Create new user
            // CreatedAt is generated with prePersist function
            $user   -> setPseudo($this->createPseudo($firstName))
                    -> setEmail($faker->email)
                    -> setPassword($this->passwordEncoder->encodePassword($user,'password'))
                    -> setGender($gender)
                    -> setFirstName($firstName)
                    -> setLastName($faker->lastName)
                    -> setBirthDate($birthDate)
                    -> setSituation($situation)
                    -> setAvatar($picture)
                    -> setDescription($faker->sentence());

            $manager->persist($user);
        }
        $manager->flush();
    }
}
