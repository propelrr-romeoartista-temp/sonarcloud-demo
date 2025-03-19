<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
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

    public function findBySearchQueryBuilder(?string $query): QueryBuilder
    {
        $qb = $this->createQueryBuilder('u');

        if ($query) {
            $qb->andWhere('u.email LIKE :email')
                ->setParameter('email', '%' . $query . '%')
                ->orWhere('u.username LIKE :username')
                ->setParameter('username', '%' . $query . '%')
                ->orWhere('u.firstName LIKE :first_name')
                ->setParameter('first_name', '%' . $query . '%')
                ->orWhere('u.lastName LIKE :last_name')
                ->setParameter('last_name', '%' . $query . '%');
        }

        $qb->orderBy('u.id', 'DESC');

        return $qb;
    }
}
