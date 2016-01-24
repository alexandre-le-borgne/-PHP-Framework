<?php

class TwitterController extends Controller
{
    function addTwitterStreamAction(Request $request)
    {
        $categoryTitle = $request->post('category');
        $firstUpdate = $request->post('firstUpdate');
        $channel = $request->post('channel');
        $userId = $request->getSession()->get('id');
        $channel = str_replace('@', '', $channel);

        $this->loadModel('CategoryModel');
        $this->loadModel('TwitterModel');

        if (!($this->twittermodel->isValidChannel($channel)))
        {
            $data = array('errors' => array('La chaine n\'existe pas, veuillez spécifier une chaine existante'));
            $this->render('layouts/addStream', $data);
            return;
        }
        $twitterEntity = $this->twittermodel->createStream($channel, $firstUpdate);

        if ($twitterEntity)
        {
            $categoryEntity = $this->categorymodel->createCategory($userId, $categoryTitle);

            $streamCategoryEntity = new StreamCategoryEntity();
            $streamCategoryEntity->setCategory($categoryEntity->getId());
            $streamCategoryEntity->setStream($twitterEntity->getId());
            $streamCategoryEntity->setStreamType(ArticleModel::TWITTER);
            $streamCategoryEntity->persist();
            $this->twittermodel->streamCron($twitterEntity);
            $this->redirectToRoute('index');
        }
        else
        {
            $this->render('layouts/addStream', array('errors' => array('Une erreur est survenue dans la connexion au flux twitter. Veuillez réssayer ! ')));
        }
    }
}