<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 23/01/2016
 * Time: 21:19
 */
class ArticleController extends Controller
{
    public function FavorisAction(Request $request)
    {
        if (!$request->getSession()->isGranted(Session::USER_IS_CONNECTED))
        {
            $this->redirectToRoute('index');
        }
        else
        {
            $this->loadModel('CategoryModel');
            $this->loadModel('ArticleModel');
            $categories = $this->categorymodel->getByUserId($request->getSession()->get('id'));
            $articles = $this->articlemodel->getArticlesFavorisByUserId($request->getSession()->get('id'), 0, 50);
            $data = array('title' => 'Mes favoris', 'categories' => $categories, 'articles' => $articles);
            $this->render('layouts/home', $data);
        }
    }

    public function ArticleAction(Request $request, $id)
    {
        //$id est rempli par l'url : aaron/publicarticle/54651346546132, l'id de l'article
        $this->loadModel('ArticleModel');
        $article = $this->articlemodel->getById($id);
        $this->render('layouts/article', array('article' => $article));
    }
}