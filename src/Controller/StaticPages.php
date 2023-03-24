<?php
namespace App\Controller;

use App\Entity\Voyage;
use App\Form\VoyageType;
use App\Repository\VoyageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Commentaire;
use App\Form\CommentaireType;
use Symfony\Component\HttpFoundation\Request;

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
            'voyages' => $voyageRepository->findBy([], ['id' => 'DESC']),
        ]); 
    }

    #[Route('/voyage/{id}', name: 'app_voyage_show', methods: ['GET'])]
    public function show(Request $request, Voyage $voyage, VoyageRepository $voyageRepository): Response
    {   
        $points = json_decode($voyage->getObject());

        // Créer un nouveau commentaire
        $commentaire = new Commentaire();

        // Créer un formulaire pour le nouveau commentaire
        $form = $this->createForm(CommentaireType::class, $commentaire);

        // Gérer la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Ajouter le nouveau commentaire au voyage et le sauvegarder
            $voyage->addCommentaire($commentaire);
            $voyageRepository->save($voyage, true);

            // Rediriger l'utilisateur vers la page de détails du voyage
            return $this->redirectToRoute('app_voyage_show_admin', ['id' => $voyage->getId()]);
        }

        return $this->render('voyage.html.twig', [
            'voyage' => $voyage,
            'points' => $points,
            'form' => $form->createView(),
        ]);
    }

    /**
    * @Route("/{id}/commentaire", name="app_voyage_commentaire", methods={"GET", "POST"})
    */
    public function ajouterCommentaire(Request $request, $id): Response
    {
        $voyage = $this->getDoctrine()->getRepository(Voyage::class)->find($id);

        if (!$voyage) {
            throw $this->createNotFoundException('Le voyage n\'existe pas');
        }

        $commentaire = new Commentaire();
        $commentaire->setVoyage($voyage);

        $form = $this->createForm(CommentaireType::class, $commentaire);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaire = $form->getData();

            // Récupérer l'utilisateur actuel et le définir comme l'utilisateur du commentaire
            $utilisateur = $this->getUser();
            $commentaire->setUser($utilisateur);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commentaire);
            $entityManager->flush();

            $this->addFlash('success', 'Votre commentaire a été ajouté');

            return $this->redirectToRoute('app_voyage_show', ['id' => $id]);
        }

        return $this->render('voyage/commentaire_ajouter.html.twig', [
            'form' => $form->createView(),
            'voyage' => $voyage,
        ]);
    }
}
