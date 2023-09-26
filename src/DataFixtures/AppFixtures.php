<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Faker\Generator;
use App\Entity\Grade;
use App\Entity\Subject;
use App\Entity\InfoUser;
use App\Entity\SchoolClass;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    // Propriété pour encoder le mot de passe
    private $encoder;

    // Propriété pour le faker
    private Generator $faker;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
        $this->faker = Factory::create('fr_Fr');
    }

    public function load(ObjectManager $manager): void
    {
        // On appelle la function loadGrade
        $this->loadGrade($manager);

        // On appelle la function loadSubject
        $this->loadSubject($manager);

        // On appelle la function loadSchoolClass
        $this->loadSchoolClass($manager);

        // On appelle la function loadInfoUser
        $this->loadInfoUser($manager);

        // On appelle la function loadUser
        $this->loadUser($manager);


        $manager->flush();
    }

    // Méthode qui alimente grade
    public function loadGrade(ObjectManager $manager)
    {
        $gradeArray = [
            '6ème',
            '5ème',
            '4ème',
            '3ème',
        ];

        foreach ($gradeArray as $key => $value) {

            $grade = new Grade();
            $grade->setLabel($value);
            $manager->persist($grade);
            $this->addReference('grade_' . $key + 1, $grade);
        }
    }

    // Méthode qui alimente Subject
    public function loadSubject(ObjectManager $manager)
    {
        $subjectArray = [
            ['key' => 1, 'label' => 'Français',              'imagePath' => 'fa-solid fa-wine-glass'],
            ['key' => 2, 'label' => 'Mathématiques',         'imagePath' => 'fa-solid fa-compass-drafting'],
            ['key' => 3, 'label' => 'Histoire-Géographie',   'imagePath' => 'fa-solid fa-earth-americas'],
            ['key' => 4, 'label' => 'Anglais',               'imagePath' => 'fa-solid fa-mug-hot'],
            ['key' => 5, 'label' => 'Espagnol',              'imagePath' => 'fa-solid fa-place-of-worship'],
            ['key' => 6, 'label' => 'Allemand',              'imagePath' => 'fa-solid fa-beer-mug-empty'],
            ['key' => 7, 'label' => 'Physique-Chimie',       'imagePath' => 'fa-solid fa-flask-vial'],
            ['key' => 8, 'label' => 'SVT',                   'imagePath' => 'fa-solid fa-leaf'],
            ['key' => 9, 'label' => 'Technologie',           'imagePath' => 'fa-solid fa-microship'],
            ['key' => 10, 'label' => 'Arts Plastiques',       'imagePath' => 'fa-solid fa-palette'],
            ['key' => 11, 'label' => 'Musique',               'imagePath' => 'fa-solid fa-drum'],
            ['key' => 12, 'label' => 'EPS',                   'imagePath' => 'fa-solid fa-futbol'],
            ['key' => 13, 'label' => 'Latin',                 'imagePath' => 'fa-solid fa-book-skull'],
            ['key' => 14, 'label' => 'Grec',                  'imagePath' => 'fa-solid fa-landmark-dome'],
        ];

        foreach ($subjectArray as $value) {

            $subject = new Subject();
            $subject->setLabel($value['label']);
            $subject->setImagePath($value['imagePath']);
            $manager->persist($subject);
            $this->addReference('subject_' . $value['key'], $subject);
        }
    }

    // Méthode qui alimente Schooclass
    public function loadSchoolClass(ObjectManager $manager)
    {
        $schoolClassArray = [
            ['key' => 1, 'grade' => 1, 'label' => '601'],
            ['key' => 2, 'grade' => 1, 'label' => '602'],
            ['key' => 3, 'grade' => 1, 'label' => '603'],
            ['key' => 4, 'grade' => 2, 'label' => '501'],
            ['key' => 5, 'grade' => 2, 'label' => '502'],
            ['key' => 6, 'grade' => 2, 'label' => '503'],
            ['key' => 7, 'grade' => 3, 'label' => '401'],
            ['key' => 8, 'grade' => 3, 'label' => '402'],
            ['key' => 9, 'grade' => 3, 'label' => '403'],
            ['key' => 10, 'grade' => 4, 'label' => '301'],
            ['key' => 11, 'grade' => 4, 'label' => '302'],
            ['key' => 12, 'grade' => 4, 'label' => '303'],
        ];

        foreach ($schoolClassArray as $value) {

            $schoolClass = new SchoolClass();
            $schoolClass->setLabel($value['label']);
            $schoolClass->setGrade($this->getReference('grade_' . $value['grade']));
            $manager->persist($schoolClass);
            $this->addReference('schoolClass_' . $value['key'], $schoolClass);
        }
    }

    // Méthode qui alimente InfoUser
    public function loadInfoUser(ObjectManager $manager)
    {
        for ($i = 1; $i <= 75; $i++) {

            $infoUser = new InfoUser();
            $infoUser->setLastname($this->faker->lastName);
            $infoUser->setFirstname($this->faker->firstName);
            $infoUser->setAddress($this->faker->streetAddress);
            $infoUser->setZipcode($this->faker->postcode);
            $infoUser->setCity($this->faker->city);
            $infoUser->setCountry('France');
            $infoUser->setPhone($this->faker->phoneNumber);
            $infoUser->setBirthDate($this->faker->dateTimeBetween($startDate = '-18 years', $endDate = '-10 years', $timezone = null));
            $manager->persist($infoUser);
            $this->addReference('infoUser_' . $i, $infoUser);
        }
    }

    public function loadUser(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('admin@admin.com')
            ->setPassword($this->encoder->hashPassword($user, 'admin'))
            ->setRoles(['ROLE_ADMIN'])
            ->setInfoUser($this->getReference('infoUser_1'));
        $manager->persist($user);
        $this->addReference('user_1', $user);

        // On crée les 14 profs
        for ($i = 2; $i <= 15; $i++) {
            $user = new User();
            $user->setEmail($this->faker->email);
            $user->setPassword($this->encoder->hashPassword($user, 'prof'));
            $user->setRoles(['ROLE_PROF']);
            $user->setInfoUser($this->getReference('infoUser_' . $i));
            $user->addSubject($this->getReference('subject_' . $i - 1));
            $manager->persist($user);
            $this->addReference('user_' . $i, $user);
        }

        for ($i = 16; $i <= 75; $i++) {
            $user = new User();
            $user->setEmail($this->faker->email);
            $user->setPassword($this->encoder->hashPassword($user, 'eleve'));
            $user->setRoles(['ROLE_ELEVE']);
            $user->setInfoUser($this->getReference('infoUser_' . $i));
            // Ici les 5 premiers eleves devront avoir la reference schoolClass_1
            // Les 5 suivants la reference schoolClass_2, etc
            $user->setSchoolClass($this->getReference('schoolClass_' . ceil(($i - 15) / 5)));
            $manager->persist($user);
            $this->addReference('user_' . $i, $user);
        }
    }
}
