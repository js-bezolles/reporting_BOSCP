<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class ModerateurRepository extends EntityRepository
{
    public function findModByPartenaire($idPartenaire)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('m')
            ->from('AppBundle:Moderateur', 'm')
            ->leftjoin('m.partenaires', 'p')
            ->addSelect('p')
            ->orderBy('m.nom', 'ASC');
        if ($idPartenaire == 0){
            $qb->where('p.id is null');
        }else if ($idPartenaire > 0){
            $qb->where('p.id = :id')
                ->setParameter('id', $idPartenaire);
        }
        return $qb->getQuery()->getResult();
    }

}