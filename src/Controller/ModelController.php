<?php

namespace App\Controller;

use App\Entity\Model;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ModelController extends AbstractController
{
    /**
     * Models
     */
    #[Route('/modeles', name: 'app_models')]
    public function models(EntityManagerInterface $em): Response
    {
        // Get all the models in the database 
        $models = $em->getRepository(Model::class)->findAll();  

        return $this->render('model/models.html.twig', [
            'models' => $models, 
        ]);
    }
}
