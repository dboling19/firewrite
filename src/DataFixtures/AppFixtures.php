<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Tag;
use App\Entity\ArticleTag;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Datetime;

class AppFixtures extends Fixture
{
  public function __construct(private UserPasswordHasherInterface $hasher) {}

  public function load(ObjectManager $manager): void
  {
    // creating the starting user with already verified account.
    $user = new User;
    $user->setUsername('testUser');
    $user->setEmail('testuser@example.com');
    $user->setPassword($this->hasher->hashPassword($user, 'password'));
    $user->setDatetime(new Datetime('now'));
    $user->setRoles(['USER_ROLE', 'ROLE_VERIFIED']);
    $manager->persist($user);


    // creating tags
    $tag_names = ['General', 'News', 'Review', 'Recipe', 'Consipiracy'];
    $tags = [];
    foreach ($tag_names as $tag_name)
    {
      $tag = new Tag;
      $tag->setName($tag_name);
      $tags[] = $tag;
      $manager->persist($tag);  
    }

    // article generation
    $article = new Article;
    $article->setTitle('Test Article');
    $article->setBody('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.');
    $article->setSummary('Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
    $article->setUser($user);
    $article->setDatetime(new Datetime('now'));
    $manager->persist($article);

    // ArticleTags - connecting relationship between articles and tags
    foreach ($tags as $tag)
    {
      $article_tag = new ArticleTag;
      $article_tag->setTag($tag);
      $article_tag->setArticle($article);
      $article_tag->setDatetime(new Datetime('now'));
      $manager->persist($article_tag);
    }

    $comment = new Comment;
    $comment->setBody('This article sucks!');
    $comment->setArticle($article);
    $comment->setDatetime(new datetime('now'));
    $comment->setUser($user);
    $manager->persist($comment);

    $manager->flush();
  }
}
