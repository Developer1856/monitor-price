<?php

namespace App\Repository;

use App\Entity\Subscriber;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Subscriber>
 *
 * @method Subscriber|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subscriber|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subscriber[]    findAll()
 * @method Subscriber[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubscriberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry )
    {
        parent::__construct($registry, Subscriber::class);
    }

    public function save(Subscriber $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Subscriber $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function findOrCreate($value): ?Subscriber
    {
        $subscriber = $this->createQueryBuilder('s')
            ->andWhere('s.email = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult();
        if ($subscriber == null) {
            $subscriber = new Subscriber();
            $subscriber->setEmail($value);
            $this->save($subscriber);
        }
        return $subscriber;
    }
}
