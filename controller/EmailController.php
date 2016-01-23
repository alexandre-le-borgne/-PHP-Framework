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

    function addEmailStreamAction(Request $request)
    {
        $server = $request->post('server');
        $account = $request->post('user');
        $password = $request->post('password');
        $port = $request->post('port');
        $userId = $request->getSession()->get('id');
        $this->loadModel('EmailModel');
        $emailentity = $this->emailmodel->createEmailStream($server, $account, $password, $port);


        $categoryTitle = $request->post('category');

        if (!($channel && $firstUpdate && $categoryTitle))
            throw new Exception('Pas tous les arguments inseres');

        //Chargement des models
        $this->loadModel('CategoryModel');
        /** @var CategoryModel $categoryModel */
        $categoryModel = $this->categorymodel;
        $this->loadModel('TwitterModel');
        /** @var TwitterModel $twitterModel */
        $twitterModel = $this->twittermodel;

        //Creation du flux
        $dateTime = new DateTime();
        $dateTime->setTimestamp(strtotime($firstUpdate));
        $twitterModel->createStream($channel, $dateTime);


        $defaultCategory = null;
        $category = null;

        $categories = $categoryModel->getByUserId($userId);
        foreach ($categories as $cat)
        {
            /** @var CategoryEntity $cat */
            if ($cat->getTitle() == 'Twitter')
                $defaultCategory = $cat;
            if ($cat->getTitle() == $categoryTitle)
                $category = $cat;
        }

        $streamCategory = new StreamCategoryEntity();
        $streamCategory->setId($userId);
        $streamCategory->setStreamType(ArticleModel::TWITTER);
        $streamCategory->setStream($twitterModel->getStreamByChannel($channel)->getId());

        if ($category)
        {
            //La categorie donnee en param existe, on ajoute donc le stream cree dans cette categorie.
            //On insere alors une ligne dans stream_category
            $streamCategory->setCategory($category->getId());
            $streamCategory->persist();
        }
        else
        {
            if ($defaultCategory)
            {
                //On place le stream dans la categorie par defaut, qui s'appelle Twitter
                $streamCategory->setCategory($defaultCategory->getId());
                $streamCategory->persist();
            }
            else
            {
                //On cree la categorie par defaut, qui s'appelle Twitter, puis on place le stream dans celle la
                $newDefaultCategory = new CategoryEntity();
                $newDefaultCategory->setAccount($userId);
                $newDefaultCategory->setTitle('Twitter');
                $newDefaultCategory->persist();
                $streamCategory->setCategory($newDefaultCategory->getId());
                $streamCategory->persist();
            }
        }

        $twitterEntity = new TwitterEntity();
        $twitterEntity->setChannel($channel);
        $twitterModel->streamCron($twitterEntity);

        //

        //

        //

        //

        //

        //

        //Todo redirection sur le home, on affiche les streams

        //Creation de la categorie

        /*
         *
         * Ajouter un stream :
            Nom categorie
            Les champs pour chaque stream Twitter/email/etc

            Le bouton de soumission du formulaire
            Action a faire :
            Une categorie existe ? Si non on créer un categorie avec le nom passé dans le formulaire
            On creer le stream
            On créér un stream Category qu'on persist, avec l'id su stream, son type et la catégorie qui a été créé ou celle qui existe
            pour acceder aux categories des utilisateur : CategoryModel => getByUserId($id)
         */
    }

}