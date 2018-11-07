<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 3/12/2018
 * Time: 12:40 PM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\BlogComment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;

class BlogController extends Controller
{
    public function blogCategoryAction()
    {
        $blogCategories = $this->getDoctrine()->getRepository('AppBundle:BlogCategory')->findBy([], ['blogCategoryOrder' => 'ASC'], 5);
        return $this->render('parts/blog-category.html.twig', array(
            'blogCategories' => $blogCategories,
        ));
    }

    /**
     * @Route("/blog-list", name="blog_list_page")
     */
    public function newListAction()
    {
        $em = $this->getDoctrine()->getManager();
//        $blogCategories = $em->getRepository('AppBundle:BlogCategory')->findBy([], ['blogCategoryOrder' => 'ASC']);
        $date = date('Y-m-d H:i:s');
        $query = $em->createQuery("SELECT b,
                       (SELECT COUNT(bcm.id)  FROM AppBundle:BlogComment AS bcm WHERE bcm.blog = b.id AND bcm.isApproved = 1)
                       FROM AppBundle:Blog AS b
                       WHERE  b.isTop = 1  ORDER BY b.blogCreatedAt DESC");
        $blogs = $query->getResult();

        $blogCategoriesquery = $em->createQuery("SELECT bc,
                        (SELECT COUNT(b.id) FROM AppBundle:Blog b WHERE b.blogCategory = bc.id and b.isTop = 1)
                        
                       FROM AppBundle:BlogCategory  bc
                      ");
        $blogCategories = $blogCategoriesquery->getResult();

        return $this->render('blog/index.html.twig', array(
            'blogCategories' => $blogCategories,
            'blogs' => $blogs,
        ));
    }

    /**
     * @Route("/blogs/{slug}/{paginate}", name="blog_category_page")
     */
    public function blogListAction(Request $request, $slug, $paginate = 1)
    {

        $em = $this->getDoctrine()->getManager();

//        $pageRepository = $em->getRepository('AppBundle:Page')->find(4);
        $blogCategory = $em->getRepository('AppBundle:BlogCategory');
        $blognews = $em->getRepository('AppBundle:Blog');

        $blogCategorys = $blogCategory->findBy(
            array(),
            array('blogCategoryOrder' => "ASC")
        );

        $postsPerPage = 10;
        $paginationTotal = 1;
        $blogRepository = $this->getDoctrine()->getRepository('AppBundle:Blog');


        $offset = $paginate - 1;
        if ($paginate !== 1) {
            $offset = $postsPerPage * $paginate - $postsPerPage;
        }

        $dateNow = Date('y-m-d');

        $blogsTotalCountQuery = $blogRepository->createQueryBuilder('b')
            ->select('b')
            ->join('b.blogCategory', 'bc')
            ->where('b.blogCreatedAt < :now')
            ->andWhere('bc.blogCategorySlug = :slug')
            ->orderBy('b.blogCreatedAt', 'DESC')
            ->setParameter('now', $dateNow)
            ->setParameter('slug', $slug)
            ->getQuery()->execute();

        $blogsTotalCount = count($blogsTotalCountQuery);

        $paginationTotal = ceil($blogsTotalCount / $postsPerPage);

        $bc = $em->getRepository('AppBundle:BlogCategory')->findOneBy(['blogCategorySlug' => $slug]);

        $query = $em->createQuery("SELECT b

                        FROM AppBundle:Blog AS b

                        JOIN AppBundle:BlogCategory AS bc WITH bc.id = b.blogCategory

                        WHERE bc.blogCategorySlug = '$slug' ")
            ->setMaxResults($postsPerPage)
            ->setFirstResult($offset);

        $blogs = $query->execute();

        return $this->render("blog/category.html.twig",
            array(
                'bc' => $bc,
                'blogs' => $blogs,
                'blogCategorys' => $blogCategorys,
                'paginate' => $paginate,
                'paginationTotal' => $paginationTotal

            ));


    }

    /**
     * @Route("/blog/{slug}", name="blog_view_page")
     */
    public function blogViewAction(Request $request, $slug)
    {
        $em = $this->getDoctrine()->getManager();
        $blognews = $em->getRepository('AppBundle:Blog');

        $query = $em->createQuery("SELECT b,
                (SELECT COUNT(bc) FROM AppBundle:BlogComment AS bc WHERE bc.blog = b AND bc.isApproved = 1)

                FROM AppBundle:Blog as b  WHERE b.blogSlug = '$slug'");


        $blog = $query->getSingleResult();


        $blogCategory = $em->getRepository('AppBundle:BlogCategory')->find($blog[0]->getBlogCategory());

        $blogRecent = $blognews->findBy(
            array('blogCategory' => $blogCategory),
            array('blogCreatedAt' => "DESC"), 5

        );

//
        $comments = $em->createQuery(
            'SELECT bc.blogCommentName, bc.createdAt, u.username
              FROM AppBundle:BlogComment bc
              Join AppBundle:Agency u with bc.user=u
              wHERE bc.blog = :blog and
               bc.isApproved = 1
            ')->setParameter('blog', $blog[0])
            ->execute();


        $form = $this->createFormBuilder()
            ->add('commentText', TextareaType::class)
            ->add('send', SubmitType::class, array('label' => " Post Comment"))
            ->getForm();

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $user = $this->getUser();
            $data = $form->getData();
            $blogComment = new BlogComment();

            $blogComment->setblogCommentName($data['commentText']);
            $blogComment->setblog($blog[0]);
            $blogComment->setUser($user);

            $em->persist($blogComment);
            $em->flush();

            return $this->redirectToRoute('blog_view_page',
                array(
                    'slug' => $slug
                )
            );

        }

        return $this->render('blog/view.html.twig',
            array(
                'form' => $form->createView(),
                'blog' => $blog,
                'blogCategory' => $blogCategory,
                'blogRecents' => $blogRecent,
                'comments' => $comments
            ));


    }


}