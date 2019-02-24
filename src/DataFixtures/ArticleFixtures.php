<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Tag;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ArticleFixtures extends BaseFixtures implements DependentFixtureInterface
{
    private static $articleTitles = [
        'Why Asteroids Taste Like Bacon',
        'Life on Planet Mercury: Tan, Relaxing and Fabulous',
        'Light Speed Travel: Fountain of Youth or Fallacy',
    ];
    private static $articleImages = [
        'asteroid.jpeg',
        'mercury.jpeg',
        'lightspeed.png',
    ];
    private static $articleAuthors = [
        'Mike Ferengi',
        'Amy Oort',
    ];

    public function loadData(ObjectManager $manager)
    {
        $this->createMany(Article::class, 10, function (Article $article, $count) use ($manager) {
            $article->setTitle($this->faker->randomElement(self::$articleTitles))
                ->setContent(<<<EOF
            capicola biltong frankfurter boudin cupim officia . Exercitation fugiat consectetur ham . Adipisicing
picanha shank et filet mignon pork belly ut ullamco . Irure velit turducken ground round doner incididunt
occaecat lorem meatball prosciutto quis strip steak .
            Meatball adipisicing ribeye bacon strip steak eu . Consectetur ham hock pork hamburger enim strip steak
mollit quis officia meatloaf tri - tip swine . Cow ut reprehenderit, buffalo incididunt in filet mignon
strip steak pork belly aliquip capicola officia . Labore deserunt esse chicken lorem shoulder tail consectetur
cow est ribeye adipisicing . Pig hamburger pork belly enim . Do porchetta minim capicola irure pancetta chuck
fugiat .
EOF
                );
            // publish most articles
            if ($this->faker->boolean(70)) {
                $article->setPublishedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            }
            $article->setAuthor($this->faker->randomElement(self::$articleAuthors))
                ->setHeartCount($this->faker->numberBetween(5, 100))
                ->setImageFilename($this->faker->randomElement(self::$articleImages));

            /**@var $tags Tag */
            $tags = $this->getRandomReferences(Tag::class, $this->faker->numberBetween(0,5));
            foreach ($tags as $tag){
                $article->addTag($tag);
            }
        });
        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        return [
            TagFixtures::class
        ];
    }
}
