<?php

namespace App\Repository;

use App\Entity\Student;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Student|null find($id, $lockMode = null, $lockVersion = null)
 * @method Student|null findOneBy(array $criteria, array $orderBy = null)
 * @method Student[]    findAll()
 * @method Student[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Student::class);
    }

    // /**
    //  * @return Student[] Returns an array of Student objects
    //  */

    
    public function findAllBis()
    {
        $qb = $this->createQueryBuilder('s');
        return $qb->getQuery()->getResult();
    }

    // select * from student where ...
    // besoin de joindre les entités Student et Team
    public function findByTeam($teamName)
    {
        $students = $this->createQueryBuilder('s')
                ->join('s.team', 't')
                ->where('t.name = :teamName')
                ->setParameter('teamName', $teamName)
                ->orderBy('s.name', 'ASC')
                ->getQuery()
                ->getResult()
                ;

        return $students;
    }

    public function findMajors()
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery(
            'SELECT s FROM App\Entity\Student s
              WHERE s.age >= 18
              ORDER BY s.name ASC');

        return $query->getResult();
    }

    public function findInName($str)
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery(
            'SELECT s FROM App\Entity\Student s
                WHERE s.name LIKE :str
            '
        )->setParameter('str', '%' . $str . '%');

        return $query->getResult();
    }

    

}
