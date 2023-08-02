<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Article;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Datetime;

class AppFixtures extends Fixture
{
  public function __construct(private UserPasswordHasherInterface $hasher) {}

  public function load(ObjectManager $manager): void
  {
    $user = new User;
    $user->setUsername('testUser');
    $user->setEmail('testuser@example.com');
    $user->setPassword($this->hasher->hashPassword($user, 'password'));
    $user->setRoles(['USER_ROLE', 'ROLE_VERIFIED']);

    $manager->persist($user);

    $article = new Article;
    $article->setTitle('Test Article');
    $article->setBody('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.');
    $article->setUser($user);
    $article->setDate(new Datetime('now'));

    $manager->persist($article);

    $manager->flush();
  }
}
