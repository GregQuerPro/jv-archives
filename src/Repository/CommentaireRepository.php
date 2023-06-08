<?php

namespace App\Repository;

use App\Entity\Commentaire;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends ServiceEntityRepository<Commentaire>
 *
 * @method Commentaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commentaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commentaire[]    findAll()
 * @method Commentaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commentaire::class);
    }

    public function save(Commentaire $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Commentaire $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Commentaire[] Returns an array of Commentaire objects
     */
    public function findLastCommentsByUserID($id): array
    {
        return $this->createQueryBuilder('c')
            ->join('c.author', 'u')
            ->where("c.author = :userID")
            ->setParameter('userID', $id)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(12)
            ->getQuery()
            ->getResult();
    }

    public function findAllLastComments(UserInterface $user): Query
    {
        return $this->createQueryBuilder('c')
            ->join('c.author', 'u')
            ->where("c.author = :user")
            ->setParameter("user", $user)
            ->orderBy('c.updatedAt', 'DESC')
            ->getQuery();
    }

    public function findLastCommentsByDuration(UserInterface $user, string $duration): Query
    {


        $date = (new \DateTime())->sub(\DateInterval::createFromDateString("1 $duration"));
        $date = $date->format('Y-m-d H:i:s');

        return $this->createQueryBuilder('c')
            ->join('c.author', 'u')
            ->andWhere('c.author = :user')
            ->setParameter('user', $user)
            ->andWhere('c.updatedAt <= :date')
            ->setParameter('date', $date)
            ->orderBy('c.updatedAt', 'DESC')
            ->getQuery();
    }

    public function removeAllByUser(UserInterface $user): void
    {
        $this->createQueryBuilder('c')
            ->delete('App\Entity\Commentaire', 'c')
            ->where('c.author = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->execute();
    }

//    public function findOneBySomeField($value): ?Commentaire
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
