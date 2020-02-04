<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    /**
     * @var SluggerInterface
     */
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        // Créer les tags
        for ($i = 1; $i <= 10; ++$i) {
            $tag = new Tag();
            $tag->setName('Tag '.$i);
            $manager->persist($tag);
        }

        // Créer les catégories
        $plainCategories = ['Smartphone', 'TV', 'PC', 'Hi-Fi'];
        $categories = [];
        foreach ($plainCategories as $plainCategory) {
            $category = new Category();
            $category->setName($plainCategory);
            $category->setSlug($this->slugger->slug($plainCategory)->lower());
            $manager->persist($category);
            $categories[] = $category;
        }

        // Créer les utilisateurs
        $users = []; // Le tableau va nous aider à stocker les instances des users
        for ($i = 1; $i <= 10; ++$i) {
            $user = new User();
            $user->setUsername($faker->email);
            $manager->persist($user);
            $users[] = $user;
        }

        // Créer les produits
        for ($i = 1; $i <= 100; ++$i) {
            $product = new Product();
            $product->setName('iPhone '.$i);
            $product->setSlug($this->slugger->slug($product->getName())->lower());
            $product->setDescription('Un iPhone de '.rand(2000, 2020));
            $product->setPrice(rand(10, 1000) * 100);
            $product->setUser($users[rand(0, 9)]);
            $product->setCategory($categories[rand(0, 3)]);
            $manager->persist($product);
        }

        $manager->flush();
    }
}
