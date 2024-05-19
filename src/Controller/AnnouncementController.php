<?php

namespace App\Controller;

use App\Entity\Announcement;
use App\Form\AnnouncementType;
use App\Repository\AnnouncementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/announcement')]
class AnnouncementController extends AbstractController
{
    #[Route('/', name: 'app_announcement_index', methods: ['GET'])]
    public function index(AnnouncementRepository $announcementRepository): Response
    {
        return $this->render('announcement/index.html.twig', [
            'announcements' => $announcementRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_announcement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AnnouncementRepository $announcementRepository): Response
    {
        $announcement = new Announcement();
        $form = $this->createForm(AnnouncementType::class, $announcement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $announcementRepository->add($announcement);
            return $this->redirectToRoute('app_announcement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('announcement/new.html.twig', [
            'announcement' => $announcement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_announcement_show', methods: ['GET'])]
    public function show(Announcement $announcement): Response
    {
        return $this->render('announcement/show.html.twig', [
            'announcement' => $announcement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_announcement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Announcement $announcement, AnnouncementRepository $announcementRepository): Response
    {
        $form = $this->createForm(AnnouncementType::class, $announcement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $announcementRepository->add($announcement);
            return $this->redirectToRoute('app_announcement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('announcement/edit.html.twig', [
            'announcement' => $announcement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_announcement_delete', methods: ['POST'])]
    public function delete(Request $request, Announcement $announcement, AnnouncementRepository $announcementRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$announcement->getId(), $request->request->get('_token'))) {
            $announcementRepository->remove($announcement);
        }

        return $this->redirectToRoute('app_announcement_index', [], Response::HTTP_SEE_OTHER);
    }
}
