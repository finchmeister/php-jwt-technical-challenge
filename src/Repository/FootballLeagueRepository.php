<?php

namespace App\Repository;

use App\Entity\FootballLeague;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FootballLeague|null find($id, $lockMode = null, $lockVersion = null)
 * @method FootballLeague|null findOneBy(array $criteria, array $orderBy = null)
 * @method FootballLeague[]    findAll()
 * @method FootballLeague[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FootballLeagueRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FootballLeague::class);
    }

//    /**
//     * @return FootballLeague[] Returns an array of FootballLeague objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FootballLeague
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
