<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\SalaryRepository;
use App\Repository\CompanyRepository;
use App\Repository\JobRepository;
use App\Repository\TimeTableRepository;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search', methods: ['POST'])]
    public function index(
            Request $request,
            SalaryRepository $salaryRepository,
            CompanyRepository $companyRepository,
            JobRepository $jobRepository,
            TimeTableRepository $timeTableRepository
        ): Response {
        $value = $request->request->get('value');
        $canSearch = !(is_null($value)) && !(empty($value)) ? true : false;

        if($canSearch) {
            $salaries = $salaryRepository->findByLikeFirstnameAndName($value);
            $companies = $companyRepository->findByLikeName($value);
            $jobs = $jobRepository->findByLikeName($value);
            $timetables = $timeTableRepository->findByLikeName($value);

            return $this->render('search/index.html.twig', [
                'salaries' => $salaries,
                'companies' => $companies,
                'jobs' => $jobs,
                'timetables' => $timetables
            ]);
        }

        return $this->redirectToRoute('app_dashboard_index');
    }
}