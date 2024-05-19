<?php

namespace App\Controller;

use App\Entity\TimeTable;
use App\Form\TimeTableType;
use App\Repository\TimeTableRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/time/table')]
class TimeTableController extends AbstractController
{
    #[Route('/', name: 'app_time_table_index', methods: ['GET'])]
    public function index(TimeTableRepository $timeTableRepository): Response
    {
        return $this->render('time_table/index.html.twig', [
            'time_tables' => $timeTableRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_time_table_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TimeTableRepository $timeTableRepository): Response
    {
        $timeTable = new TimeTable();
        $form = $this->createForm(TimeTableType::class, $timeTable);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $timeTableRepository->add($timeTable);
            return $this->redirectToRoute('app_time_table_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('time_table/new.html.twig', [
            'time_table' => $timeTable,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_time_table_show', methods: ['GET'])]
    public function show(TimeTable $timeTable): Response
    {
        return $this->render('time_table/show.html.twig', [
            'time_table' => $timeTable,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_time_table_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TimeTable $timeTable, TimeTableRepository $timeTableRepository): Response
    {
        $form = $this->createForm(TimeTableType::class, $timeTable);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $timeTableRepository->add($timeTable);
            return $this->redirectToRoute('app_time_table_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('time_table/edit.html.twig', [
            'time_table' => $timeTable,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_time_table_delete', methods: ['POST'])]
    public function delete(Request $request, TimeTable $timeTable, TimeTableRepository $timeTableRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$timeTable->getId(), $request->request->get('_token'))) {
            $timeTableRepository->remove($timeTable);
        }

        return $this->redirectToRoute('app_time_table_index', [], Response::HTTP_SEE_OTHER);
    }
}
