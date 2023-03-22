<?php
namespace App\Controller;

use App\Repository\VoyageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StaticPages extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('home.html.twig');
    }

    #[Route('/voyages', name: 'voyages')]
    public function voyages(VoyageRepository $voyageRepository): Response
    {
        // // Récupération des voyages depuis la base de données
        // $voyages = $voyageRepository->findAll();

        // // Déclaration d'un tableau vide pour stocker les données de chaque voyage
        // $voyagesData = array();

        // // Boucle sur chaque voyage pour extraire les données
        // foreach ($voyages as $voyage) {
        //     $voyagesData[] = json_decode($voyage->getObject(), true);
        // }

        // // Passage des données de localisation à Twig pour les afficher
        // return $this->render('voyages.html.twig', [
        //     'voyagesData' => $voyagesData,
        // ]);

        return $this->render('voyages.html.twig', [
            'voyages' => $voyageRepository->findAll(),
        ]); 
    }

}
