<?php

namespace App\Controller;

use App\Entity\Tache;
use App\Repository\TacheRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(TacheRepository $tacheRepository): Response
    {
        $taches = $tacheRepository->findAll();
        return $this->render('dashboard\index.html.twig', [
            'taches' => $taches,
        ]);
    }

    /**
     * @Route ("/delete/all", name="deleteall")
     */
    public function deleteall(TacheRepository $repo, EntityManagerInterface $manager){
        $taches = $repo->findAll();
        foreach ($taches as $tache) {
            $manager->remove($tache);
        }
        $manager->flush();
        return $this->redirectToRoute('app_dashboard');
    }


}
