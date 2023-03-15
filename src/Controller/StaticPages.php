<?php
namespace App\Controller;

use App\Entity\Destination;
use App\Form\DestinationType;
use App\Repository\DestinationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
 
class StaticPages extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(): Response
    {
        $titre = 'Bienvenue';
 
        return $this->render('home.html.twig', [
            'titre' => $titre
        ]);
    }
}