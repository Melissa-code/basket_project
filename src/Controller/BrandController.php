<?php

namespace App\Controller;

use App\Entity\Brand;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\BrandType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BrandController extends AbstractController
{
    /**
     * List of the brands & create form
     */
    #[Route('/marques', name: 'app_brands')]
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
}
