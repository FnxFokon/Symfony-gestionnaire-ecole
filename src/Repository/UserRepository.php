<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @implements PasswordUpgraderInterface<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    // Méthode qui récupère les utilisateurs qui ont le rôle ROLE_PROF
    public function getUsersByRole($role)
    {
        $entity = $this->getEntityManager();
        $query = $entity->createQuery(
            'SELECT u, s, i
            FROM App\Entity\User u
            LEFT JOIN u.subjects s
            LEFT JOIN u.infoUser i
            WHERE u.roles LIKE :role'
        )->setParameter('role', '%"' . $role . '"%');

        return $query->getResult();
    }

    // Méthode pour enregistrer un utilisateur
    public function save(User $user, bool $flush = false): void
    {
        $this->getEntityManager()->persist($user);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    // Méthode qui recupere les utilisateur seulon leur classe
    public function getUsersByClass($class)
    {
        $entity = $this->getEntityManager();
        $query = $entity->createQuery(
            'SELECT u,  i
            FROM App\Entity\User u
            JOIN u.infoUser i
            WHERE u.schoolClass = :class'
        )->setParameter('class', $class);

        return $query->getResult();
    }
}
