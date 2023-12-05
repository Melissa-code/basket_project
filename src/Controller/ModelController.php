<?php

namespace App\Controller;

use App\Entity\Model;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ModelType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/modeles')]
class ModelController extends AbstractController
{
    /**
     * Models
     */
    #[Route('/', name: 'app_models')]
    public function models(EntityManagerInterface $em, Request $request): Response
    {
        // Create a new model
        $model = new Model(); 
        $form = $this->createForm(ModelType::class, $model); 
        $form->handleRequest($request); 
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($model); 
            $em->flush(); 

            $this->addFlash(
                'success',
                'Nouveau modèle créé!'
            );
        }

        // Get all the models in the database 
        $models = $em->getRepository(Model::class)->findAll(); 

        return $this->render('model/models.html.twig', [
            'models' => $models, 
            'add_form' => $form->createView(),
        ]);
    }

        /**
     * Detail of a model and update a model
     */
    #[Route('/{id}', name: 'app_model')]
    public function model(EntityManagerInterface $em, Model $model = null, Request $request): Response
    {
        // Check if the model exists
        if ($model == null) {
            $this->addFlash(
                'danger',
                'Modèle introuvable!'
            );
            return $this->redirectToRoute('app_models'); 
        } 
 
        // Update form
        $form = $this->createForm(ModelType::class, $model); 
        $form->handleRequest($request); 
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($model); 
            $em->flush(); 
            $this->addFlash(
                'success',
                'Le modèle a bien été modifié!'
            );
        }
  
        return $this->render('model/model.html.twig', [
            'model' => $model, 
            'update' => $form->createView(),
        ]);
    }
}
