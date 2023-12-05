<?php

namespace App\Controller;

use App\Entity\Brand;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\BrandType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/marques')]
class BrandController extends AbstractController
{
    /**
     * List of the brands & create form
     */
    #[Route('/', name: 'app_brands')]
    public function brands(EntityManagerInterface $em, Request $request): Response
    {
        // Create a new brand
        $brand = new Brand(); 
        $form = $this->createForm(BrandType::class, $brand); 
        $form->handleRequest($request); 
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($brand); 
            $em->flush(); 

            $this->addFlash(
                'success',
                'Nouvelle marque créée!'
            );
        }

        // Get all the brands in the database 
        $brands = $em->getRepository(Brand::class)->findAll();  

        return $this->render('brand/brands.html.twig', [
            'brands' => $brands,
            'add_form' => $form->createView(),
        ]);
    }

    /**
     * Detail of a brand and update a brand
     */
    #[Route('/{id}', name: 'app_brand')]
    public function brand(EntityManagerInterface $em, Brand $brand = null, Request $request): Response
    {
        // Check if the beand exists
        if ($brand == null) {
            $this->addFlash(
                'danger',
                'Marque introuvable!'
            );
            return $this->redirectToRoute('app_brands'); 
        } 
 
        // Update form
        $form = $this->createForm(BrandType::class, $brand); 
        $form->handleRequest($request); 
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($brand); 
            $em->flush(); 
            $this->addFlash(
                'success',
                'La marque a bien été modifiée!'
            );
        }
  
        return $this->render('brand/brand.html.twig', [
            'brand' => $brand, 
            'update' => $form->createView(),
        ]);
    }
}
