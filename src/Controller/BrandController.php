<?php

namespace App\Controller;

use App\Entity\Brand;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BrandController extends AbstractController
{
    /**
     * Brands
     */
    #[Route('/marques', name: 'app_brands')]
    public function brands(EntityManagerInterface $em): Response
    {
        // Get all the brands in the database 
        $brands = $em->getRepository(Brand::class)->findAll();  

        return $this->render('brand/brands.html.twig', [
            'brands' => $brands,
        ]);
    }
}
