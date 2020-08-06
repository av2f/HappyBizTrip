<?php

// *********************************************************
// BE CAREFUL : before performing fixtures, comment the line
// $this->setIsSubscribed(false);
// in User entity => function setInitialUser()
// *********************************************************

namespace App\DataFixtures;

use Faker;
use DateInterval;
use App\Entity\User;
use App\Entity\Visit;
use App\Entity\Connect;
use App\Entity\Interest;
use App\Entity\InterestType;
use App\Entity\Subscription;
use App\Entity\SubscripType;
use App\Entity\SubscriptionHistory;
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
        $number=(string)mt_rand(100,999);
        $firstN=(strlen($firstName)>5) ? substr($firstName,0,strlen($firstName)-3) : $firstName;
        $pseudo=mb_strtolower($firstN).$number;
        $search=array("/é/","/è/","/ê/","/ë/");
        $pseudo = preg_replace($search,'e',$pseudo);
        return $pseudo;
    }
    
    public function load(ObjectManager $manager)
    {
        $NB_USER = 200;
        $entryUser = array();
        
        // interestType
        $type = array(
            ['nameType' => 'interest.type_culture', 'iconType' => 'fas fa-film'],
            ['nameType' => 'interest.type_science_business', 'iconType' => 'fas fa-chart-line'],
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
            ['name' => 'interest.science_finance', 'raw' => '5'],
            ['name' => 'interest.science_well_being', 'raw' => '6']
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

        // Subscription Type
        $subType = array(
            ['type' => 'subscription.type_occasional', 'duration' => 1, 'durationType' => 'W', 'price' => 8.99],
            ['type' => 'subscription.type_punctual', 'duration' => 1, 'durationType' => 'M', 'price' => 28.99],
            ['type' => 'subscription.type_temporary', 'duration' => 3, 'durationType' => 'M', 'price' => 75.52],
            ['type' => 'subscription.type_regular', 'duration' => 6, 'durationType' => 'M', 'price' => 129.46],
            ['type' => 'subscription.type_permanent', 'duration' => 12, 'durationType' => 'M', 'price' => 215.76]
        );
        $subscriptionTypeArray=[];
        foreach($subType as $nb => $infos) {
            $subscripType = new SubscripType();
            $subscripType->setType($infos['type']);
            $subscripType->setDuration($infos['duration']);
            $subscripType->setDurationType($infos['durationType']);
            $subscripType->setPrice($infos['price']);
            $manager->persist($subscripType);
            $subscriptionTypeArray[]=$subscripType;
        }
        $manager->flush();

        // User
        $faker = Faker\Factory::create('fr_FR');
        // Define Genders (W=Woman / M=Man)
        $genders = array('W', 'M');
        // Define Situations (C=Couple / S=Single / W=Widow)
        $situations = array('C', 'S', 'K');
        // Define state of Connect
        $states = array('W', 'C', 'B');
        
        $subscribes = array(true, false);
        
        for ($i=0; $i<$NB_USER; $i++){
            $user = new User();

            // generate randomly if subscribed or not
            $subscribed=$subscribes[mt_rand(0, count($subscribes)-1)];
            
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
                    -> setPhoneNumber($faker->phoneNumber)
                    -> setSituation($situation)
                    -> setAvatar($picture)
                    -> setDescription($faker->sentence())
                    -> setCompany($faker->company)
                    -> setProfession($faker->jobTitle)
                    -> setIsSubscribed(false);
            
            if ($subscribed) { $user->setIsSubscribed(true); }
            $manager->persist($user);
            array_push($entryUser, $user);

            // Create subscription  
            if ($subscribed){
                $dateBegin=new \DateTime();
                $subscription=$subscriptionTypeArray[mt_rand(0, count($subscriptionTypeArray)-1)];
                switch($subscription->getDurationType()){
                    case "W":
                        $dateBegin=$faker->dateTimeBetween('-6 days','-1 day');
                        break;
                    case 'M':
                        switch($subscription->getDuration()){
                            case 1:
                                $dateBegin=$faker->dateTimeBetween('-20 days','-3 days');
                                break;
                            case 3:
                                $dateBegin=$faker->dateTimeBetween('-2 months','-5 days');
                                break;
                            case 6:
                                $dateBegin=$faker->dateTimeBetween('-4 months','-1 month');
                                break;
                            case 12:
                                $dateBegin=$faker->dateTimeBetween('-10 months','-20 days');
                                break;
                        }
                    break;
                }
                $dateEnd=clone $dateBegin;
                $interval="P".$subscription->getDuration().$subscription->getDurationType();
                $dateEnd->add(new DateInterval($interval));
                $subscribe=new Subscription();
                $subscribe  -> setSubscriber($user)
                            -> setSubscriberType($subscription)
                            -> setSubscribPayAt($dateBegin)
                            -> setSubscribBeginAt($dateBegin)
                            -> setSubscribEndAt($dateEnd);

                $manager->persist($subscribe);
            }

            // subscription History
            if ($subscribed){
                $j = mt_rand(1,5);
                for ($u=0; $u<=$j;$u++) {
                    $dateBegin=new \DateTime();
                    $subscription=$subscriptionTypeArray[mt_rand(0, count($subscriptionTypeArray)-1)];
                    switch($subscription->getDurationType()){
                        case "W":
                            $dateBegin=$faker->dateTimeBetween('-360 days','-9 day');
                            break;
                        case 'M':
                            switch($subscription->getDuration()){
                                case 1:
                                    $dateBegin=$faker->dateTimeBetween('-24 months','-1 month');
                                    break;
                                case 3:
                                    $dateBegin=$faker->dateTimeBetween('-36 months','-3 months');
                                    break;
                                case 6:
                                    $dateBegin=$faker->dateTimeBetween('-60 months','-6 months');
                                    break;
                                case 12:
                                    $dateBegin=$faker->dateTimeBetween('-48 months','-12 months');
                                    break;
                            }
                        break;
                    }
                    $dateEnd=clone $dateBegin;
                    $interval="P".$subscription->getDuration().$subscription->getDurationType();
                    $dateEnd->add(new DateInterval($interval));
                    $subscribe = new SubscriptionHistory();
                    $subscribe  -> setSubscriber($user)
                                -> setSubscriberType($subscription)
                                -> setSubscribPayAt($dateBegin)
                                -> setSubscribBeginAt($dateBegin)
                                -> setSubscribEndAt($dateEnd);

                    $manager->persist($subscribe);
                }
            }
        }
        // Handle visitor
        foreach ($entryUser as $u) {
            if (mt_rand(0,1) == 1) { // randomly if visited
                $visitors = array_rand($entryUser, mt_rand(2, 20));
                for ($i=0;$i<count($visitors);$i++) {
                    $v = $entryUser[$visitors[$i]];                    
                    if ($u->getPseudo() != $v->getPseudo()) {
                        $visit = new Visit();
                        $visit -> setVisited($u);
                        $visit -> setVisitor($v);
                        $manager->persist($visit);
                    }
                }
            }
        }

        // Handle connect
        foreach ($entryUser as $u) {
            if (mt_rand(0,1) == 1) { // randomly if connect
                $requests = array_rand($entryUser, mt_rand(2, 20));
                for ($i=0;$i<count($requests);$i++) {
                    $r = $entryUser[$requests[$i]];                    
                    if ($u->getPseudo() != $r->getPseudo()) {
                        $connect = new Connect();
                        $connect -> setRequester($u);
                        $connect -> setRequested($r);
                        $connect -> setActionAt(new \DateTime());
                        $state=$states[mt_rand(0, count($states)-1)];
                        $connect -> setState($state);
                        $manager->persist($connect);
                    }
                }
            }
        }

        // Handle messages
        foreach($entryUser as $u) {
            // do something
        }

        $manager->flush();
    }
}
