<?php

namespace Blog\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Blog\BlogBundle\Entity\Article;
use Blog\BlogBundle\Form\ArticleType;


class ArticleController extends Controller
{

    public function indexAction(Request $request)
    {

        $session = $request->getSession();
        $visited = $session->get("visitedArticles");

        $articles = $this
            ->getDoctrine()
            ->getRepository('BlogBlogBundle:Article')
            ->findAll();

        $content = $this
            ->get('templating')
            ->render('BlogBlogBundle:Article:index.html.twig', array(
                'blog_title' => "Mon petit blog de Simplon",
                'articles' => $articles,
                'visitedArticles' => $visited
            ));

        return new Response($content);
    }

    public function viewAction(Request $request, int $id)
    {

        $article = $this
            ->getDoctrine()
            ->getRepository('BlogBlogBundle:Article')
            ->find($id);

        $session = $request->getSession();

        if (!$session->get('visitedArticles')){
            $session->set("visitedArticles", array());
        }

        $visited = $session->get("visitedArticles");

        if (array_search($article, $visited) === false){
            array_push($visited, $article);
            $session->set("visitedArticles", $visited);
        }

        $content = $this
            ->get('templating')
            ->render('BlogBlogBundle:Article:view.html.twig', array(
                'article' => $article,
                'visitedArticles' => $visited
            ));

        return new Response($content);
    }

    public function addAction(Request $request)
    {

        $article = new Article();
        $article->setDate(new \DateTime());

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $article = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirect('http://localhost:8888/blog/web/app_dev.php/');
        }

        return $this->render('BlogBlogBundle:Article:add.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function deleteAction(int $id)
    {

        $article = $this
            ->getDoctrine()
            ->getRepository('BlogBlogBundle:Article')
            ->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($article);
        $em->flush();

        return $this->redirect('http://localhost:8888/blog/web/app_dev.php/');

    }

    public function clearSessionAction(Request $request)
    {

        $session = $request->getSession();
        $session->invalidate();

        return $this->redirect('http://localhost:8888/blog/web/app_dev.php');

    }

    public function adminAction()
    {
        return new Response('<html><body>Admin page!</body></html>');
    }

}