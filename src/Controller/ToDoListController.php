<?php

namespace App\Controller;

use App\Entity\Tache;
use App\Form\AjoutTacheType;
use App\Repository\TacheRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ToDoListController extends AbstractController
{
    /**
     * @Route("/", name= "home")
     */
    public function index(TacheRepository $repo): Response
    {
        $taches = $repo->findAll();
        return $this->render('to_do_list/index.html.twig', [
            'taches' => $taches,
        ]);
    }

    /**
     * @Route("/new", name="ajoutertache")
     * @Route("/edit/{id}", name="modifier")
     */
    public function ajoutTache(Tache $tache = null, Request $request, EntityManagerInterface $manager):Response
    {
        if (!$tache) {
            $tache = new Tache();
        }

        $form = $this->createForm(AjoutTacheType::class, $tache);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();

            $manager->persist($tache);
            // envoi des données sur la bdd
            $manager->flush();
            return $this->redirectToRoute('home');
        }

        return $this->renderForm('to_do_list/new.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete (Tache $tache = null, EntityManagerInterface $manager){
        $manager->remove($tache);
        $manager->flush();
        return $this->redirectToRoute('home');
    }

}
