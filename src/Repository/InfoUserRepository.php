<?php

namespace App\Repository;

use App\Entity\InfoUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InfoUser>
 *
 * @method InfoUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method InfoUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method InfoUser[]    findAll()
 * @method InfoUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InfoUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InfoUser::class);
    }

    // Méthode pour enregistrer les infos de l'utilisateur
    public function save(InfoUser $infoUser, bool $flush = false): void
    {
        $this->getEntityManager()->persist($infoUser);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
