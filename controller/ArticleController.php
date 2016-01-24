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
            $favoris = $this->articlemodel->getIdOfFavoris($request->getSession()->get('id'));
            $data = array('title' => 'Mes favoris', 'categories' => $categories, 'articles' => $articles, 'favoris' => $favoris);
            $this->render('layouts/home', $data);
        }
    }

    public function CategoryAction(Request $request, $id) {
        if (!$request->getSession()->isGranted(Session::USER_IS_CONNECTED))
        {
            $this->redirectToRoute('index');
        }
        else
        {
            $this->loadModel('CategoryModel');
            $this->loadModel('ArticleModel');
            /** @var CategoryEntity $categoryEntity */
            $categoryEntity = $this->categorymodel->getById($id);
            if($categoryEntity && $categoryEntity->getAccount() == $request->getSession()->get('id')) {
                $categories = $this->categorymodel->getByUserId($request->getSession()->get('id'));
                $articles = $this->articlemodel->getArticlesByCategoryId($categoryEntity->getId(), 0, 50);
                $favoris = $this->articlemodel->getIdOfFavoris($request->getSession()->get('id'));
                $data = array('title' => $categoryEntity->getTitle(), 'categories' => $categories, 'articles' => $articles, 'favoris' => $favoris);
                $this->render('layouts/home', $data);
            }
            else {
                $this->redirectToRoute('index');
            }
        }
    }

    public function ArticleAction(Request $request, $id)
    {
        //$id est rempli par l'url : aaron/publicarticle/54651346546132, l'id de l'article
        $this->loadModel('ArticleModel');
        $article = $this->articlemodel->getById($id);
        if($article)
        {
            $this->render('layouts/article', array('article' => $article));
        }
        else {
            $this->redirectToRoute('index');
        }
    }
}