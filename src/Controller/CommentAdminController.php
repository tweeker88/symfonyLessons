<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class CommentAdminController
 * @package App\Controller
 * @isGranted("ROLE_ADMIN")
 */
class CommentAdminController extends BaseController
{
    /**
     * @Route("/admin/comment", name="comment_admin")
     * @param CommentRepository $repository
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(CommentRepository $repository, Request $request, PaginatorInterface $paginator)
    {
//        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $q = $request->query->get('q');

        $queryBuilder = $repository->findAllWithSearch($q);

        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );

        return $this->render('comment_admin/index.html.twig', [
            'pagination' => $pagination
        ]);
    }
}
