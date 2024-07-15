<?php

namespace App\Repository;

use App\Entity\Facture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Facture>
 */
class FactureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Facture::class);
    }
    //génération du numéro de facture (par exemple pour le 7 juillet 2024 et la troisième facture , on aura: 07072024-003)
    public function generateFactureNumber():string
    {
        $date = new \DateTime();
        $datePart = $date->format('dmY');
        $qb = $this->createQueryBuilder('f')
            ->select('count(f.id)')
            ->where('f.date = :today')
            ->setParameter('today', $date->format('Y-m-d'));
        $factureCount = (int) $qb->getQuery()->getSingleScalarResult();
        return $datePart . '-' . ($factureCount+1);
    }
    //    /**
    //     * @return Facture[] Returns an array of Facture objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('f.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Facture
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
