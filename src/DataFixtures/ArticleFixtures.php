<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Common\Persistence\ObjectManager;

class ArticleFixtures extends BaseFixtures
{
    public function loadData(ObjectManager $manager)
    {
        $this->createMany(Article::class, 10, function (Article $article, $count) {
            $article->setTitle('Why Asteroids Taste Like Bacon')
                ->setSlug('why-asteroids-taste-like-bacon-' . $count)
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
            if (rand(1, 10) > 2) {
                $article->setPublishedAt(new \DateTime(sprintf('-%d days', rand(1, 100))));
            }
            $article->setAuthor('Mike Ferengi')
                ->setHeartCount(rand(5, 100))
                ->setImageFilename('asteroid.jpeg');
        });
        $manager->flush();
    }
}
