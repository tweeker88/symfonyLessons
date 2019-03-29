<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Service\SlackClient;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Provider\Base;
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
class ArticleController extends BaseController
{

    private $slack;

    public function __construct(Client $slack)
    {
        $this->slack = $slack;
    }

    /**
     * @Route("/", name="app_homepage")
     * @param ArticleRepository $repository
     * @return Response
     * @throws \Doctrine\ORM\Query\QueryException
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
    public function show(Article $article)
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/show/{slug}/heart", name="article_toggle_heart", methods={"POST"})
     * @var $slug
     * @return JsonResponse
     */
    public function toggleArticleHeart(Article $article, EntityManagerInterface $em)
    {
        $article->incrementHeartCount();
        $em->flush();

        return new JsonResponse(['hearts' => $article->getHeartCount()]);
    }


}