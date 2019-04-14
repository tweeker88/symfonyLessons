<?php

namespace App\Controller;


use App\Entity\Article;
use App\Form\ArticleFormType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ArticleAdminController
 * @package App\Controller
 */
class ArticleAdminController extends BaseController
{


    /**
     * @Route("/admin/article/new/")
     * @IsGranted("ROLE_ADMIN_ARTICLE")
     */
    public function new()
    {

        $form = $this->createForm(ArticleFormType::class);

        return $this->render('article_admin/new.html.twig',[
            'articleForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/article/{id}/edit")
     * @param Article $article
     * @IsGranted("MANAGE", subject="article")
     */
    public function edit(Article $article)
    {
//        if(!$this->isGranted('MANAGE', $article)){
//            throw $this->createAccessDeniedException('No access!');
//        }
        dd($article);
    }
}