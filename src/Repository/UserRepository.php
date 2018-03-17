<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class UserRepository.
 */
class UserRepository extends ServiceEntityRepository
{
    /**
     * UserRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findAllAdminUsers()
    {
        return $this->createQueryBuilder('user')
            ->where('user.something LIKE \'%{ADMIN}%\'')
            ->orderBy('user.lastName', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findAllStudentUsers()
    {
        return $this->createQueryBuilder('user')
            ->where('user.something LIKE \'%{STUDENT}%\'')
            ->orderBy('user.lastName', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
}
