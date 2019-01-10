<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Service\SlackClient;
use Doctrine\ORM\EntityManagerInterface;
use Michelf\MarkdownInterface;
use Nexy\Slack\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ArticleController
 * @package App\Controller
 */
class ArticleController extends AbstractController
{

    private $slack;

    public function __construct(Client $slack)
    {
        $this->slack = $slack;
    }

    /**
     * @Route("/", name="app_homepage")
     * @return Response
     */
    public function homepage(ArticleRepository $repository)
    {
        $articles = $repository->findAllPublishedOrderedByNewest();

        return $this->render('article/homepage.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/news/{slug}", name="article_show")
     * @return Response
     * @var string
     * @var MarkdownInterface
     */
    public function show(Article $article, SlackClient $slack, EntityManagerInterface $em)
    {

        $comments = [
            'I ate a normal rock once. It did NOT taste like bacon!',
            'Woohoo! I\'m going on an all-asteroid diet!',
            'I like bacon too! Buy some from my site! bakinsomebacon.com',
        ];

        return $this->render('article/show.html.twig', [
            'article' => $article,
            'comments' => $comments,
        ]);
    }

    /**
     * @Route("/show/{slug}/heart", name="article_toggle_heart", methods={"POST"})
     * @var $slug
     * @return JsonResponse
     */
    public function toggleArticleHeart($slug)
    {
        return new JsonResponse(['hearts' => rand(5, 100)]);
    }


}