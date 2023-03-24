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

class VoyageCommentaireController extends AbstractController
{
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