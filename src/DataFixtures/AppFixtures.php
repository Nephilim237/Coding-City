<?php

namespace App\DataFixtures;

use App\Entity\AboutUser;
use App\Entity\Category;
use App\Entity\Image;
use App\Entity\Post;
use App\Entity\User;
use App\Entity\UserNaming;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = Factory::create('fr_FR');

        for ($u = 0; $u < 5; $u++) {
            $user = new User();
            $userNaming = new UserNaming();
            $userPic = new Image();
            $userBio = new AboutUser();
            $category = new Category();

            $userNaming
                ->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setCountry($faker->country())
                ->setOccupation($faker->jobTitle())
                ->setUsername($faker->userName())
                ->setUser($user);

            $userBio
                ->setBio($faker->text())
                ->setUser($user);

            $profilePicUrl = 'https://randomuser.me/api/portraits/' . $faker->randomElement(['men', 'women']) . '/' . $faker->numberBetween(1, 90);
            $userPic
                ->setImage($profilePicUrl)
                ->setUser($user);


            $password = $this->hasher->hashPassword($user, '1234567890');
            $user
                ->setUserNaming($userNaming)
                ->setEmail($faker->email())
                ->setImage($userPic)
                ->setAboutUser($userBio)
                ->setRoles($faker->randomElements(["ROLE_ADMIN", "ROLE_SUPER_ADMIN"]))
                ->setPassword($password);

            $category
                ->setTitle($faker->word())
                ->setAuthor($user);

            $manager->persist($category);

            for ($p = 0; $p < mt_rand(1, 4); $p++) {
                $post = new Post();
                $post
                    ->setAuthor($user)
                    ->setTitle($faker->sentence($faker->numberBetween(6, 24)))
                    ->setImageIllustration($faker->image('public/images/post/banner', 360, 360, 'animals', false, true))
                    ->setContent($faker->paragraphs(mt_rand(2, 6), true))
                    ->setActive(false)
                    ->addCategory($category);
                $manager->persist($post);
            }

            $manager->persist($user);
        }

        for ($u = 0; $u < 20; $u++) {
            $user = new User();
            $userNaming = new UserNaming();
            $userPic = new Image();
            $userBio = new AboutUser();

            $userNaming
                ->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setCountry($faker->country())
                ->setOccupation($faker->jobTitle())
                ->setUsername($faker->userName())
                ->setUser($user);

            $userBio
                ->setBio($faker->text())
                ->setUser($user);

            $profilePicUrl = 'https://randomuser.me/api/portraits/' . $faker->randomElement(['men', 'women']) . '/' . $faker->numberBetween(1, 90);
            $userPic
                ->setImage($profilePicUrl)
                ->setUser($user);


            $password = $this->hasher->hashPassword($user, '1234567890');
            $user
                ->setUserNaming($userNaming)
                ->setEmail($faker->email())
                ->setImage($userPic)
                ->setAboutUser($userBio)
                ->setRoles(["ROLE_USER"])
                ->setPassword($password);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
