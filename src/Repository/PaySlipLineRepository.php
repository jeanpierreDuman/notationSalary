<?php

namespace App\Repository;

use App\Entity\PaySlipLine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PaySlipLine>
 *
 * @method PaySlipLine|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaySlipLine|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaySlipLine[]    findAll()
 * @method PaySlipLine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaySlipLineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaySlipLine::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(PaySlipLine $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(PaySlipLine $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // Get sum of amount (amountSalary/amountEmployee)
    public function getSumOfAmount()
    {
        $qb = $this->createQueryBuilder('psl');

        $qb->select([
            'SUM(psl.amountSalary) as amountSalary',
            'SUM(psl.amountEmploye) as amountEmployee'
        ]);

        return $qb->getQuery()->getSingleResult();
    }
}
