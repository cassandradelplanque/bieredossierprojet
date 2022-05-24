<?php

namespace App\Controller;

use App\Entity\Note;
use App\Form\NoteType;
use App\Repository\NoteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/note")
 */
class NoteController extends AbstractController
{
   

    /**
     * @Route("/new", name="app_note_new", methods={"GET", "POST"})
     */
    public function new(Request $request, NoteRepository $noteRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $note = new Note();
        $form = $this->createForm(NoteType::class, $note);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $note->setBiereId($_GET['beer_id']);
            $note->setPerso($this->getUser());
            $noteRepository->add($note, true);
            
            return $this->redirectToRoute('home_fiche', ['beer'=> $_GET['beer_id']], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('note/new.html.twig', [
            'note' => $note,
            'form' => $form,
            'beer_id'=>$_GET['beer_id']
        ]);
    }

   
    

    /**
     * @Route("/{id}/edit", name="app_note_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Note $note, NoteRepository $noteRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $form = $this->createForm(NoteType::class, $note);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $noteRepository->add($note, true);

            return $this->redirectToRoute('home_fiche', ['beer'=> $_GET['beer_id']], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('note/edit.html.twig', [
            'note' => $note,
            'form' => $form,
            'beer_id'=>$_GET['beer_id']
        ]);
    }

   
}
