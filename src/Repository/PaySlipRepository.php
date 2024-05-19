<?php

namespace App\Repository;

use App\Entity\PaySlip;
use App\Entity\Salary;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PaySlip>
 *
 * @method PaySlip|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaySlip|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaySlip[]    findAll()
 * @method PaySlip[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaySlipRepository extends ServiceEntityRepository
{
    private $criticalSalaryRepository;
    private $settingRepository;

    public function __construct(ManagerRegistry $registry,
                                CriticalSalaryRepository $criticalSalaryRepository,
                                SettingRepository $settingRepository)
    {
        parent::__construct($registry, PaySlip::class);
        $this->criticalSalaryRepository = $criticalSalaryRepository;
        $this->settingRepository = $settingRepository;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(PaySlip $entity, bool $flush = true): void
    {
        $globalAmountSalary = 0;
        $globalAmountEmployee = 0;

        foreach($entity->getPaySlipLine() as $paySlipLine) {
            // Initial amount salary/employee
            $amountSalary = 0;
            $amountEmployee = 0;

            // Compute amount salary
            if($paySlipLine->getRateSalary() !== null) {
                $rateSalary = $paySlipLine->getRateSalary() / 100;
                $amountSalary = $paySlipLine->getBase() * $rateSalary;
                $globalAmountSalary += $amountSalary;
            } else {
                $paySlipLine->setRateSalary(0);
            }

            // Compute amount employee
            if($paySlipLine->getRateEmploye() !== null) {
                $rateEmployee = $paySlipLine->getRateEmploye() / 100;
                $amountEmployee = $paySlipLine->getBase() * $rateEmployee;
                $globalAmountEmployee += $amountEmployee;
            } else {
                $paySlipLine->setRateEmploye(0);
            }

            // Update amount salary/employee
            $paySlipLine->setAmountSalary($amountSalary);
            $paySlipLine->setAmountEmploye($amountEmployee);

            $paySlipLine->setPaySlip($entity);
        }

        if($entity->getSalary() instanceof Salary) {
            $isScoreImpact = $entity->getSalary()->getIsScoreImpacted();

            $criticalData = $this->criticalSalaryRepository->getCriticalData($entity->getSalary());
            $score = $criticalData['average'];
            $counter = $criticalData['counter'];

            if($isScoreImpact === true && $counter >= 25) {
                $setting = $this->settingRepository->findOneBy([]);
                $startInterval = $setting->getStartIntervalScore();
                $endInterval = $setting->getEndIntervalScore();
                $amountScore = $setting->getAmountScore();

                if($score >= $endInterval) {
                    $globalAmountSalary+= $amountScore;
                } elseif($score <= $startInterval) {
                    $globalAmountSalary-= $amountScore;
                }
            }
        }

        $entity->setAmountSalary($globalAmountSalary);
        $entity->setAmountEmploye($globalAmountEmployee);

        $this->_em->persist($entity);

        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(PaySlip $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
