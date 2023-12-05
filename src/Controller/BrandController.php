<?php

namespace App\Controller;

use App\Entity\Brand;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\BrandType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
            // Upload a logo image
            $imageFile = $form->get('logo')->getData();
            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();
                // Move the file to the directory where brochures are stored
                try {
                    $imageFile->move(
                        $this->getParameter('upload_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('danger', 'Impossible d\'ajouter le logo.'); 
                }
                $brand->setLogo($newFilename);
            }

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
            // Edit the logo
            $imageFile = $form->get('logo')->getData();
            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();
                // Move the file to the directory where brochures are stored
                try {
                    $imageFile->move(
                        $this->getParameter('upload_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('danger', 'Impossible d\'ajouter le logo.'); 
                }
                $brand->setLogo($newFilename);
            }

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

    /**
     * Delete a brand
     */
    #[Route('/delete/{id}', name: 'delete_brand')]
    public function delete(EntityManagerInterface $em, Brand $brand = null): RedirectResponse
    {
        if ($brand == null) {
            $this->addFlash(
                'danger',
                'Marque introuvable!'
            );
            return $this->redirectToRoute('app_brands'); 
        }

        // Remove the brand 
        $em->remove($brand); //Prepare sauvg
        $em->flush(); 
        $this->addFlash(
            'warning',
            'Marque supprimée!'
        );
        return $this->redirectToRoute('app_brands'); 
    }
}
