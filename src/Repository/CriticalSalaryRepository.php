<?php

namespace App\Repository;

use App\Entity\CriticalSalary;
use App\Entity\Salary;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<CriticalSalary>
 *
 * @method CriticalSalary|null find($id, $lockMode = null, $lockVersion = null)
 * @method CriticalSalary|null findOneBy(array $criteria, array $orderBy = null)
 * @method CriticalSalary[]    findAll()
 * @method CriticalSalary[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CriticalSalaryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CriticalSalary::class);
    }

    public function getCriticalData(Salary $salary)
    {
        $qb = $this->createQueryBuilder('cs');

        $qb->leftJoin('cs.salary', 'salary')->addSelect('salary');
        $qb->leftJoin('cs.critical', 'critical')->addSelect('critical');

        $qb->where($qb->expr()->eq('salary', ':salary'));

        $qb->setParameter(':salary', $salary);

        $qb->select([
            'COALESCE(AVG(critical.notation), 0) as average',
            'COUNT(critical.id) as counter'
        ]);

        return $qb->getQuery()->getSingleResult();
    }

    public function getIdCriticalBySalary(Salary $salary, $limit = null)
    {
        $qb = $this->createQueryBuilder('cs');

        $qb->leftJoin('cs.salary', 'salary')->addSelect('salary');
        $qb->leftJoin('cs.critical', 'critical')->addSelect('critical');

        $qb->where($qb->expr()->eq('salary', ':salary'));

        $qb->setParameter(':salary', $salary);

        if($limit !== null) {
            $qb->setMaxResults($limit);
        }

        $qb->select('critical.id');

        return $qb->getQuery()->getResult();
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(CriticalSalary $entity, bool $flush = true): void
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
    public function remove(CriticalSalary $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return CriticalSalary[] Returns an array of CriticalSalary objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CriticalSalary
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
