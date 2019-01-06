<?php

namespace App\Controller;


use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleAdminController extends AbstractController
{


    /**
     * @Route("/admin/article/new/{slug}")
     */
    public function new(EntityManagerInterface $em,string $slug)
    {
        $article = new Article();

        $article->setTitle('new Article')
            ->setSlug($slug . random_int(1,999))
            ->setContent('new Content');

        if (random_int(1, 10) > 2) {
            $article->setPublishedAt(new \DateTime(sprintf('-%d days', rand(1, 100))));
        }

        $em->persist($article);
        $em->flush();


        return new Response(sprintf(
            'Hiya! New Article id: #%d slug: %s',
            $article->getId(),
            $article->getSlug()
        ));
    }
}