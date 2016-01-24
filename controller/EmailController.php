<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 18/01/2016
 * Time: 22:37
 */
class EmailController extends Controller
{
    public function IndexAction($id = null) // Fonction de debug pour le dev
    {
        if (intval($id)) {
            $this->loadModel('ArticleModel');
            /** @var ArticleEntity $article */
            $article = $this->articlemodel->getById($id);
            echo $article->getContent();
        }
    }

    function AddEmailStreamAction(Request $request) {
        $server = $request->post('server');
        $account = $request->post('user');
        $password = $request->post('password');
        $port = $request->post('port');
        $category = $request->post('category');
        $firstUpdate = $request->post('firstUpdate');
        $user = $request->getSession()->get('id');
        $this->loadModel('EmailModel');
        $this->loadModel('CategoryModel');
        /** @var EmailEntity $emailEntity */
        $emailEntity = $this->emailmodel->createEmailStream($server, $account, $password, $port, $firstUpdate);
        /** @var CategoryEntity $categoryEntity */
        $categoryEntity = $this->categorymodel->createCategory($user, $category);
        $streamCategoryEntity = new StreamCategoryEntity();
        $streamCategoryEntity->setCategory($categoryEntity->getId());
        $streamCategoryEntity->setStream($emailEntity->getId());
        $streamCategoryEntity->setStreamType(ArticleModel::EMAIL);
        $streamCategoryEntity->persist();
        $this->redirectToRoute('index');
    }
}