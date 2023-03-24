<?php

namespace App\Controller;

use App\Entity\Voyage;
use App\Form\VoyageType;
use App\Repository\VoyageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Commentaire;
use App\Form\CommentaireType;

#[Route('/admin')]
class VoyageController extends AbstractController
{
    #[Route('/', name: 'app_voyage_admin', methods: ['GET'])]
    public function index(VoyageRepository $voyageRepository): Response
    {
        // // Récupération des voyages depuis la base de données
        // $voyages = $voyageRepository->findAll();

        // // Déclaration d'un tableau vide pour stocker les données de chaque voyage
        // $voyagesData = array();

        // // Boucle sur chaque voyage pour extraire les données
        // foreach ($voyages as $voyage) {
        //     $voyagesData[] = json_decode($voyage->getObject(), true);
        // }

        // Passage des données de localisation à Twig pour les afficher
        return $this->render('voyage/index.html.twig', [
            'voyages' => $voyageRepository->findBy([], ['id' => 'DESC']),
        ]); 
    }

    #[Route('/new', name: 'app_voyage_new', methods: ['GET', 'POST'])]
    public function new(Request $request, VoyageRepository $voyageRepository): Response
    {
        $voyage = new Voyage();
        $form = $this->createForm(VoyageType::class, $voyage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $voyageRepository->save($voyage, true);

            return $this->redirectToRoute('app_voyage_admin', [], Response::HTTP_SEE_OTHER);
        }

            // Définir une valeur par défaut pour la variable "points"
    $points = '[]';
    
        return $this->renderForm('voyage/new.html.twig', [
            'data' => "",
            'voyage' => $voyage,
            'form' => $form,
            'points' => $points,
        ]);
    }

    #[Route('/{id}', name: 'app_voyage_show_admin', methods: ['GET'])]
    public function show(Voyage $voyage): Response
    {
        return $this->render('voyage/show.html.twig', [
            'voyage' => $voyage,
        ]);
    }

    #[Route('/edit/{id}', name: 'app_voyage_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Voyage $voyage, VoyageRepository $voyageRepository): Response
    {
        $form = $this->createForm(VoyageType::class, $voyage);
        $form->handleRequest($request);
        $points = $voyage->getObject();

        if ($form->isSubmitted() && $form->isValid()) {
            $voyageRepository->save($voyage, true);
    
            return $this->redirectToRoute('app_voyage_admin', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('voyage/edit.html.twig', [
            'data' => "",
            'voyage' => $voyage,
            'form' => $form,
            'points' => $points,
        ]);
    }
    

    #[Route('/{id}', name: 'app_voyage_delete', methods: ['POST'])]
    public function delete(Request $request, Voyage $voyage, VoyageRepository $voyageRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$voyage->getId(), $request->request->get('_token'))) {
            $voyageRepository->remove($voyage, true);
        }

        return $this->redirectToRoute('app_voyage_admin', [], Response::HTTP_SEE_OTHER);
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
