<?php

namespace App\Repository;

use App\Entity\Salary;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Salary>
 *
 * @method Salary|null find($id, $lockMode = null, $lockVersion = null)
 * @method Salary|null findOneBy(array $criteria, array $orderBy = null)
 * @method Salary[]    findAll()
 * @method Salary[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SalaryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Salary::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Salary $entity, bool $flush = true): void
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
    public function remove(Salary $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function getSalaryBetweenTime($date)
    {
        $qb = $this->createQueryBuilder('s');

        $qb->leftJoin('s.timeTables', 'timetables')->addSelect('timetables');
        $qb->leftJoin('timetables.timeTableLine', 'timeTableLine')->addSelect('timeTableLine');

        $qb->where($qb->expr()->between(':date', 'timeTableLine.dateStart', 'timeTableLine.dateEnd'));
        $qb->setParameter(':date', $date);

        return $qb->getQuery()->getResult();
    }

    public function findByLikeFirstnameAndName($name)
    {
        $qb = $this->createQueryBuilder('s');

        $qb->where($qb->expr()->like('s.firstname', ':name'));
        $qb->orWhere($qb->expr()->like('s.lastname', ':name'));

        $qb->setParameter(':name', '%' . $name . '%');

        $qb->groupBy('s.id');

        return $qb->getQuery()->getResult();
    }
}
