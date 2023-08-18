<?php

namespace App\Repository;

use ApiPlatform\Doctrine\Orm\Paginator;
use App\Entity\Todo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Tools\Pagination\Paginator as PaginatorTools;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Todo>
 *
 * @method Todo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Todo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Todo[]    findAll()
 * @method Todo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TodoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Todo::class);
    }

    public function removeTodos(array $entities, bool $flush = false): void
    {
        foreach ($entities as $todo) {
            $this->getEntityManager()->remove($todo);
        }

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getPublicTodosPaginator(int $page, int $itemPerPage, array $order): Paginator
    {
        $qb = $this->createQueryBuilder('t')
                    ->where('t.public = :isPublic')
                    ->setParameter('isPublic', true);

        if (count($order) > 0) {
            $firstKey = array_key_first($order);
            $qb->orderBy(
                $firstKey,
                $order[$firstKey]
            );
        }

        $criteria = Criteria::create()
                    ->setFirstResult(($page - 1) * $itemPerPage)
                    ->setMaxResults($itemPerPage);

        $qb->addCriteria($criteria);

        $doctrinePaginator = new PaginatorTools($qb);

        return new Paginator($doctrinePaginator);
    }
}
