<?php

namespace App\Controller;

use App\Entity\Critical;
use App\Entity\CriticalSalary;
use App\Form\CriticalType;
use App\Repository\CriticalRepository;
use App\Repository\SalaryRepository;
use App\Utils\CriticalSalaryManager;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/critical')]
class CriticalController extends AbstractController
{
    #[Route('/', name: 'app_critical_index', methods: ['GET'])]
    public function index(CriticalRepository $criticalRepository): Response
    {
        return $this->render('critical/index.html.twig', [
            'criticals' => $criticalRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_critical_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CriticalRepository $criticalRepository, SalaryRepository $salaryRepository,
                        ManagerRegistry $managerRegistry): Response
    {
        $critical = new Critical();
        $form = $this->createForm(CriticalType::class, $critical);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $critical->setDate(new \DateTime());

            $em = $managerRegistry->getManager();
            $salaries = $salaryRepository->getSalaryBetweenTime($critical->getDate());

            foreach ($salaries as $salary) {
                $criticalSalary = new CriticalSalary();
                $criticalSalary->setCritical($critical);
                $criticalSalary->setSalary($salary);

                $em->persist($criticalSalary);
            }

            $criticalRepository->add($critical);
            return $this->redirectToRoute('app_critical_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('critical/new.html.twig', [
            'critical' => $critical,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_critical_show', methods: ['GET'])]
    public function show(Critical $critical): Response
    {
        return $this->render('critical/show.html.twig', [
            'critical' => $critical,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_critical_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Critical $critical, CriticalRepository $criticalRepository): Response
    {
        $form = $this->createForm(CriticalType::class, $critical);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $criticalRepository->add($critical);
            return $this->redirectToRoute('app_critical_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('critical/edit.html.twig', [
            'critical' => $critical,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_critical_delete', methods: ['POST'])]
    public function delete(Request $request, Critical $critical, CriticalRepository $criticalRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$critical->getId(), $request->request->get('_token'))) {
            $criticalRepository->remove($critical);
        }

        return $this->redirectToRoute('app_critical_index', [], Response::HTTP_SEE_OTHER);
    }
}
