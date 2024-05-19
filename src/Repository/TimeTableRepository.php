<?php

namespace App\Repository;

use App\Entity\TimeTable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TimeTable>
 *
 * @method TimeTable|null find($id, $lockMode = null, $lockVersion = null)
 * @method TimeTable|null findOneBy(array $criteria, array $orderBy = null)
 * @method TimeTable[]    findAll()
 * @method TimeTable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TimeTableRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TimeTable::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(TimeTable $entity, bool $flush = true): void
    {
        // Set timetable to item
        foreach($entity->getTimeTableLine() as $timeTableLine) {
            $timeTableLine->setTimeTable($entity);
        }

        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(TimeTable $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findByLikeName($name)
    {
        $qb = $this->createQueryBuilder('t');

        $qb->where($qb->expr()->like('t.name', ':name'));

        $qb->setParameter(':name', '%' . $name . '%');

        return $qb->getQuery()->getResult();
    }
}
