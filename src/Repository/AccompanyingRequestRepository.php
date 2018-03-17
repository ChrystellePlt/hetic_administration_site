<?php

namespace App\Repository;

use App\Entity\AccompanyingRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AccompanyingRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccompanyingRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccompanyingRequest[]    findAll()
 * @method AccompanyingRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccompanyingRequestRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AccompanyingRequest::class);
    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('a')
            ->where('a.something = :value')->setParameter('value', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
