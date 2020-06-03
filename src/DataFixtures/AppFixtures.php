<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\User;
use App\Entity\Interest;
use App\Entity\InterestType;
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
        // interestType
        $type = array(
            ['nameType' => 'interest.type_culture', 'iconType' => 'fas fa-film'],
            ['nameType' => 'interest.type_science_business', 'iconType' => 'fas fa-globe'],
            ['nameType' => 'interest.type_leisure', 'iconType' => 'fas fa-puzzle-piece'],
            ['nameType' => 'interest.type_meet', 'iconType' => 'fas fa-user-friends']
        );
        $iCulture = array(
            ['name' => 'interest.culture_literature', 'raw' => '1'],
            ['name' => 'interest.culture_cinema', 'raw' => '2'],
            ['name' => 'interest.culture_theater', 'raw' => '3'],
            ['name' => 'interest.culture_concert', 'raw' => '4'],
            ['name' => 'interest.culture_music', 'raw' => '5'],
            ['name' => 'interest.culture_art', 'raw' => '6'],
        );

        $iScience = array(
            ['name' => 'interest.science_new_tech', 'raw' => '1'],
            ['name' => 'interest.science_sciences', 'raw' => '2'],
            ['name' => 'interest.science_politic', 'raw' => '3'],
            ['name' => 'interest.science_business', 'raw' => '4'],
            ['name' => 'interest.science_finance', 'raw' => '5']
        );

        $iLeisure = array(
            ['name' => 'interest.leisure_sport', 'raw' => '1'],
            ['name' => 'interest.leisure_animal', 'raw' => '2'],
            ['name' => 'interest.leisure_fashion', 'raw' => '3'],
            ['name' => 'interest.leisure_art_of_live', 'raw' => '4'],
            ['name' => 'interest.leisure_garden', 'raw' => '5'],
            ['name' => 'interest.leisure_diy', 'raw' => '6'],
            ['name' => 'interest.leisure_board_game', 'raw' => '7'],
            ['name' => 'interest.leisure_role_game', 'raw' => '8'],
            ['name' => 'interest.leisure_gambling', 'raw' => '9']
        );

        $iMeet = array(
            ['name' => 'interest.meet_business', 'raw' => '1'],
            ['name' => 'interest.meet_private_f', 'raw' => '2'],
            ['name' => 'interest.meet_private_m', 'raw' => '3'],
        );

        foreach($type as $nb => $infos) {
            $interestType = new InterestType();
            $interestType->setNameType($infos['nameType']);
            $interestType->setIconType($infos['iconType']);
            $manager->persist($interestType);
            switch ($infos['nameType']) {
                case 'interest.type_culture':
                    foreach ($iCulture as $nb => $i) {
                        $interest = new Interest();
                        $interest->setName($i['name']);
                        $interest->setRaw($i['raw']);
                        $interest->setInterestType($interestType);
                        $manager->persist($interest);
                    }
                    break;
                case 'interest.type_science_business':
                    foreach ($iScience as $nb => $i) {
                        $interest = new Interest();
                        $interest->setName($i['name']);
                        $interest->setRaw($i['raw']);
                        $interest->setInterestType($interestType);
                        $manager->persist($interest);
                    }
                    break;
                case 'interest.type_leisure':
                    foreach ($iLeisure as $nb => $i) {
                        $interest = new Interest();
                        $interest->setName($i['name']);
                        $interest->setRaw($i['raw']);
                        $interest->setInterestType($interestType);
                        $manager->persist($interest);
                    }
                    break;
                case 'interest.type_meet':
                    foreach ($iMeet as $nb => $i) {
                        $interest = new Interest();
                        $interest->setName($i['name']);
                        $interest->setRaw($i['raw']);
                        $interest->setInterestType($interestType);
                        $manager->persist($interest);
                    }
                    break;
            }
        }
        $manager->flush();

        // User
        $faker = Faker\Factory::create('fr_FR');
        // Define Genders (W=Woman / M=Man)
        $genders=array('W', 'M');
        // Define Situations (C=Couple / S=Single / W=Widow)
        $situations=array('C', 'S', 'K');
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
                    -> setDescription($faker->sentence())
                    -> setCompany($faker->company);

            $manager->persist($user);
        }
        $manager->flush();
    }
}
