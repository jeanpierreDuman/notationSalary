<?php

namespace App\Controller;

use App\Entity\TimeTableLine;
use App\Form\TimeTableLineType;
use App\Repository\TimeTableLineRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/time/table/line')]
class TimeTableLineController extends AbstractController
{
    #[Route('/', name: 'app_time_table_line_show', methods: ['GET'])]
    public function index(TimeTableLineRepository $timeTableLineRepository): Response
    {
        return $this->render('time_table_line/index.html.twig', [
            'time_table_line' => $timeTableLineRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_time_table_line_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TimeTableLineRepository $timeTableLineRepository): Response
    {
        $timeTableLine = new TimeTableLine();
        $form = $this->createForm(TimeTableLineType::class, $timeTableLine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $timeTableLineRepository->add($timeTableLine);
            return $this->redirectToRoute('app_time_table_line_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('time_table_line/new.html.twig', [
            'time_table_line' => $timeTableLine,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_time_table_line_show', methods: ['GET'])]
    public function show(TimeTableLine $timeTableLine): Response
    {
        return $this->render('time_table_line/show.html.twig', [
            'time_table_line' => $timeTableLine,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_time_table_line_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TimeTableLine $timeTableLine, TimeTableLineRepository $timeTableLineRepository): Response
    {
        $form = $this->createForm(TimeTableLineType::class, $timeTableLine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $timeTableLineRepository->add($timeTableLine);
            return $this->redirectToRoute('app_time_table_line_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('time_table_line/edit.html.twig', [
            'time_table_line' => $timeTableLine,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_time_table_line_delete', methods: ['POST'])]
    public function delete(Request $request, TimeTableLine $timeTableLine, TimeTableLineRepository $timeTableLineRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$timeTableLine->getId(), $request->request->get('_token'))) {
            $timeTableLineRepository->remove($timeTableLine);
        }

        return $this->redirectToRoute('app_timetable_item_index', [], Response::HTTP_SEE_OTHER);
    }
}
