<?php

namespace App\Controller;

use App\Entity\Salary;
use App\Form\SalaryType;
use App\Repository\CriticalRepository;
use App\Repository\CriticalSalaryRepository;
use App\Repository\SalaryRepository;
use App\Repository\SettingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/salary')]
class SalaryController extends AbstractController
{
    #[Route('/', name: 'app_salary_index', methods: ['GET'])]
    public function index(SalaryRepository $salaryRepository): Response
    {
        return $this->render('salary/index.html.twig', [
            'salaries' => $salaryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_salary_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SalaryRepository $salaryRepository): Response
    {
        $salary = new Salary();
        $form = $this->createForm(SalaryType::class, $salary);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $salaryRepository->add($salary);
            return $this->redirectToRoute('app_salary_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('salary/new.html.twig', [
            'salary' => $salary,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_salary_show', methods: ['GET'])]
    public function show(Salary $salary, CriticalSalaryRepository $criticalSalaryRepository, CriticalRepository $criticalRepository): Response
    {
        $criticalData = $criticalSalaryRepository->getCriticalData($salary);
        $arrayIdCriticalBySalary = $criticalSalaryRepository->getIdCriticalBySalary($salary, 10);
        $reformatIdCritical = array_column($arrayIdCriticalBySalary, 'id');

        $criticals = $criticalRepository->findById($reformatIdCritical);

        return $this->render('salary/show.html.twig', [
            'salary' => $salary,
            'timetables' => $salary->getTimeTables(),
            'criticalData' => $criticalData,
            'criticals' => $criticals
        ]);
    }

    #[Route('/{id}/edit', name: 'app_salary_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Salary $salary, SalaryRepository $salaryRepository): Response
    {
        $form = $this->createForm(SalaryType::class, $salary);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $salaryRepository->add($salary);
            return $this->redirectToRoute('app_salary_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('salary/edit.html.twig', [
            'salary' => $salary,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_salary_delete', methods: ['POST'])]
    public function delete(Request $request, Salary $salary, SalaryRepository $salaryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$salary->getId(), $request->request->get('_token'))) {
            $salaryRepository->remove($salary);
        }

        return $this->redirectToRoute('app_salary_index', [], Response::HTTP_SEE_OTHER);
    }
}
