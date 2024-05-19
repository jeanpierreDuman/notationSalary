<?php

namespace App\Controller;

use App\Entity\Setting;
use App\Repository\CriticalRepository;
use App\Repository\PaySlipLineRepository;
use App\Repository\SettingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashBoardController extends AbstractController
{
    #[Route('/', name: 'app_dashboard_index')]
    public function index(SettingRepository $settingRepository,
                          CriticalRepository $criticalRepository,
                          PaySlipLineRepository $paySlipLineRepository): Response{
        $startIntervalScore = 0;
        $endIntervalScore = 0;
        $amountScore = 0;

        $setting = $settingRepository->findOneBy([]);
        $critics = $criticalRepository->findAll();

        if($setting instanceof Setting) {
            $startIntervalScore = $setting->getStartIntervalScore();
            $endIntervalScore = $setting->getEndIntervalScore();
            $amountScore = $setting->getAmountScore();
        }

        // Compute sum of amountSalary / amountEmployee
        $sumOfAccount = $paySlipLineRepository->getSumOfAmount();
        $sumOfAccount = [
            'amountSalary' => floatval($sumOfAccount['amountSalary']),
            'amountEmployee' => floatval($sumOfAccount['amountEmployee'])
        ];

        $totalPaid = floatval($sumOfAccount['amountSalary']) + floatval($sumOfAccount['amountEmployee']);
        $sumOfAccount = json_encode($sumOfAccount);

        return $this->render('dashboard/index.html.twig', [
            'startIntervalScore' => $startIntervalScore,
            'endIntervalScore' => $endIntervalScore,
            'amountScore' => $amountScore,
            'critics' => $critics,
            'sumOfAccount' => $sumOfAccount,
            'totalPaid' => $totalPaid
        ]);
    }
}
