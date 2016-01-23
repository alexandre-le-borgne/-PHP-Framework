<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 23/01/2016
 * Time: 21:19
 */

class IndexController extends Controller
{
    public function FavorisAction(Request $request)
    {
        if ($request->getSession()->isGranted(Session::USER_IS_CONNECTED))
        {
            $this->redirectToRoute('index');
        }
        else {
            $this->loadModel('CategoryModel');
            $this->loadModel('ArticleModel');
            $categories = $this->categorymodel->getByUserId($request->getSession()->get('id'));
            $articles = $this->articlemodel->getArticlesFavorisByUserId($request->getSession()->get('id'), 0, 50);
            $data = array('categories' => $categories, 'articles' => $articles);
            $this->render('layouts/home', $data);
        }
    }
}