<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 18/01/2016
 * Time: 22:37
 */
class EmailController extends Controller
{
    public function IndexAction($id = null)
    {
        if (intval($id)) {

        } else {
            $this->loadModel('ArticleModel');
            /** @var ArticleEntity $article */
            $article = $this->articlemodel->getById(16);
            echo "<h1>".$article->getTitle()."</h1>";
            echo "<div>".html_entity_decode($article->getContent())."</div>";
        }
    }
}