<?php

namespace App\Repository;

use App\Entity\Critical;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Critical>
 *
 * @method Critical|null find($id, $lockMode = null, $lockVersion = null)
 * @method Critical|null findOneBy(array $criteria, array $orderBy = null)
 * @method Critical[]    findAll()
 * @method Critical[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CriticalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Critical::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Critical $entity, bool $flush = true): void
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
    public function remove(Critical $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}