<?php

namespace App\Controller;

use App\Entity\CommandLine;
use App\Entity\Facture;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FactureController extends AbstractController
{


    
    #[Route("/facture/new")]
    public function new(ProductRepository $productRepository, EntityManagerInterface $em)
    {
        // récupération des produits

        $product1 = $productRepository->find(1);
        $product2 = $productRepository->find(2);

        // la création d'une / plusieurs lignes de commandes

        $commandLine1 = new CommandLine();
        $commandLine1
            ->setQuantity(10)
            ->setUnityPrice($product1->getPrice())
            ->setTotalPrice($commandLine1->getQuantity() * $product1->getPrice())
            ->setProduct($product1);

        $commandLine2 = new CommandLine();
        $commandLine2
            ->setQuantity(5)
            ->setUnityPrice($product2->getPrice())
            ->setTotalPrice($commandLine2->getQuantity() * $product2->getPrice())
            ->setProduct($product2);

        // création d'une facture

        $facture = new Facture();
        $facture->setTotalPrice($commandLine1->getTotalPrice() + $commandLine2->getTotalPrice());

        // attribuer les lignes de commandes à la facture

        $facture->addCommandLine($commandLine1)
            ->addCommandLine($commandLine2);

        // sauvegarde dans la base de données

        $em->persist($facture);
        $em->flush();

        return new Response('OK');

    }

    #[Route('/facture/{id}')]
    public function show(Facture $facture)
    {
        dd($facture);
    }
}

