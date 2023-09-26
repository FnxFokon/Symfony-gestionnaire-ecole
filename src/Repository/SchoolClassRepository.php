<?php

namespace App\Repository;

use App\Entity\SchoolClass;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SchoolClass>
 *
 * @method SchoolClass|null find($id, $lockMode = null, $lockVersion = null)
 * @method SchoolClass|null findOneBy(array $criteria, array $orderBy = null)
 * @method SchoolClass[]    findAll()
 * @method SchoolClass[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SchoolClassRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SchoolClass::class);
    }

    // Méthode qui récupere les classes par grades
    public function getClassesByGrade(array $grades)
    {
        $entityManager = $this->getEntityManager();

        // On va bouclé sur le tableau de grade
        foreach ($grades as $grade) {
            // On créer la requete
            $query = $entityManager->createQuery(
                'SELECT c
                FROM app\Entity\SchoolClass c
                WHERE c.grade = :grade'
            )->setParameter('grade', $grade);

            $classes[$grade->getLabel()] = $query->getResult();
        }

        return $classes;
    }
}
