<?php

namespace App\Controller;

use App\Entity\PaySlip;
use App\Form\PaySlipType;
use App\Repository\CriticalSalaryRepository;
use App\Repository\PaySlipRepository;
use App\Repository\SettingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/pay/slip')]
class PaySlipController extends AbstractController
{
    #[Route('/', name: 'app_pay_slip_index', methods: ['GET'])]
    public function index(PaySlipRepository $paySlipRepository, 
        CriticalSalaryRepository $criticalSalaryRepository,
        SettingRepository $settingRepository): Response
    {
        $setting = $settingRepository->findOneBy([]);
        $playslips = array_map(function(PaySlip $payslip) use ($criticalSalaryRepository, $setting) {

            $amountSalary = $payslip->getAmountSalary();

            if($payslip->getSalary()->getIsScoreImpacted() === true) {
                $criticalData = $criticalSalaryRepository->getCriticalData($payslip->getSalary());
                $salaryAverageCritical = $criticalData['average'];

                if($salaryAverageCritical <= $setting->getStartIntervalScore()) {
                    $amountSalary -= $setting->getAmountScore();
                } elseif ($salaryAverageCritical >= $setting->getEndIntervalScore()) {
                    $amountSalary += $setting->getAmountScore();
                }
            }
            
            return [
                'id' => $payslip->getId(),
                'salary' => $payslip->getSalary(),
                'startPeriod' => $payslip->getStartPeriod(),
                'endPeriod' => $payslip->getEndPeriod(),
                'type' => $payslip->getType(),
                'payAt' => $payslip->getPayAt(),
                'amountSalary' => $amountSalary,
                'amountEmploye' => $payslip->getAmountEmploye()
            ];

        }, $paySlipRepository->findAll());

        return $this->render('pay_slip/index.html.twig', [
            'pay_slips' => $playslips
        ]);
    }

    #[Route('/new', name: 'app_pay_slip_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PaySlipRepository $paySlipRepository): Response
    {
        $paySlip = new PaySlip();
        $form = $this->createForm(PaySlipType::class, $paySlip);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $paySlipRepository->add($paySlip);
            return $this->redirectToRoute('app_pay_slip_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pay_slip/new.html.twig', [
            'pay_slip' => $paySlip,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pay_slip_show', methods: ['GET'])]
    public function show(PaySlip $paySlip,
                         CriticalSalaryRepository $criticalSalaryRepository,
                         SettingRepository $settingRepository): Response
    {
        $criticalData = $criticalSalaryRepository->getCriticalData($paySlip->getSalary());
        $setting = $settingRepository->findOneBy([]);

        return $this->render('pay_slip/show.html.twig', [
            'pay_slip' => $paySlip,
            'score' => $criticalData['average'],
            'setting' => $setting
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pay_slip_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PaySlip $paySlip, PaySlipRepository $paySlipRepository): Response
    {
        $form = $this->createForm(PaySlipType::class, $paySlip);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $paySlipRepository->add($paySlip);
            return $this->redirectToRoute('app_pay_slip_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pay_slip/edit.html.twig', [
            'pay_slip' => $paySlip,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pay_slip_delete', methods: ['POST'])]
    public function delete(Request $request, PaySlip $paySlip, PaySlipRepository $paySlipRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$paySlip->getId(), $request->request->get('_token'))) {
            $paySlipRepository->remove($paySlip);
        }

        return $this->redirectToRoute('app_pay_slip_index', [], Response::HTTP_SEE_OTHER);
    }
}
