<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * DemandeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DemandeRepository extends EntityRepository
{

    public function findByUser($user,$offset,$limit)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('d')
            ->from('UserBundle:User', 'u')
            ->join('u.partenaires', 'p')
            ->join('p.moderateurs', 'm')
            ->join('AppBundle:Demande', 'd', 'WITH', 'd.moderateur = m.nom');
        if($user != null)
        {
            $qb->where('u.id = :id');
            $qb->setParameter('id', $user->getId());
        }
        if($limit != null)
            $qb->setMaxResults( $limit );
        if($offset != null)
            $qb->setFirstResult( $offset );
        if($user != null)

            return $qb->getQuery()->getResult();
    }
    public function findAllTraitees()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT p FROM AppBundle:Demande p WHERE p.date_trt IS NOT NULL ORDER BY p.date_trt DESC'
            )
            ->getResult();
    }

    public function findAllEnCours()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT p FROM AppBundle:Demande p WHERE p.date_trt IS NULL ORDER BY p.date_reception ASC'
            )
            ->getResult();
    }

    /**
     * @param $user
     * @param $debut
     * @param $fin
     * @param $num_demande
     * @param $cod_etat
     * @param $doce_origin
     * @param $ind_confirm_origin
     * @param $num_epj
     * @param $nat_pro
     * @param $date_r
     * @param $date_t
     * @param $moderateur
     * @param $im_label_dd
     * @param $offset
     * @param $limit
     * @param $estDemandeTraitee
     * @return mixed
     */
    public function findByUserAndByDate($user, $data, $offset, $limit, $estDemandeTraitee)
    {
        $debutRcpt = $data['debutRcpt'];
        $finRcpt = $data['finRcpt'];
        $debutTrt = $data['debutTrt'];
        $finTrt = $data['finTrt'];
        $num_demande= $data['num_demande'];
        $cod_etat = $data['cod_etat_demande'];
        $code_origin = $data['code_origine'];
        $ind_confirm_pro = $data['ind_confirm_pro'];
        $num_epj = $data['num_epj'];
        $nat_pro = $data['nat_pro'];
        $date_r = $data['date_reception'];
        $date_t = $data['date_trt'];
        $moderateur = $data['moderateur'];
        $im_label_dd= $data['im_label_dd'];
        $type_jour_reception = $data['type_jour_reception'];
        $type_jour_traitement = $data['type_jour_traitement'];

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('d')
            ->from('UserBundle:User', 'u')
            ->join('u.partenaires', 'p')
            ->join('p.moderateurs', 'm')
            ->join('AppBundle:Demande', 'd', 'WITH', 'd.moderateur = m.nom');
        if($user != null)
        {
            $qb->where('u.id = :id');
            $qb->setParameter('id', $user->getId());
        }
        if($num_demande != null)
        {
            $qb->andwhere('d.num_demande = :num_demande');
            $qb->setParameter('num_demande', $num_demande);
        }
        if($cod_etat != null)
        {
            $qb->andwhere($qb->expr()->like('d.cod_etat_demande', ':code_etat'));
            $qb->setParameter('code_etat', $cod_etat);
        }
        if($code_origin != null)
        {
            $qb->andwhere($qb->expr()->like('d.code_origine', ':code_orignie'));
            $qb->setParameter('code_orignie', $code_origin);
        }
        if($ind_confirm_pro != null)
        {
            $qb->andwhere($qb->expr()->like('d.ind_confirm_pro', ':ind_confirm_pro'));
            $qb->setParameter('ind_confirm_pro', $ind_confirm_pro);
        }
        if($num_epj != null)
        {
            $qb->andwhere('d.num_epj =:num_epj');
            $qb->setParameter('num_epj', $num_epj);
        }
        if($nat_pro != null)
        {
            $qb->andwhere($qb->expr()->like('d.nat_pro', ':nat_pro'));
            $qb->setParameter('nat_pro', $nat_pro);
        }
        if($date_r != null)
        {
            $qb->andwhere($qb->expr()->like('d.date_reception', ':date_r'));
            $qb->setParameter('date_r', $date_r);
        }
        if($date_t != null)
        {
            $qb->andwhere($qb->expr()->like('d.date_trt', ':date_t'));
            $qb->setParameter('date_t', $date_t);
        }
        if($moderateur != null)
        {
            $qb->andwhere($qb->expr()->like('d.moderateur', ':moderateur'));
            $qb->setParameter('moderateur', $moderateur);
        }
        if($im_label_dd != null)
        {
            $qb->andwhere('d.im_label_dd = :im_label_dd');
            $qb->setParameter('im_label_dd', $im_label_dd);
        }
        if($debutRcpt != null)
        {
            $qb->andwhere('d.date_reception >= :debut');
            $qb->setParameter('debut', $debutRcpt);
        }
        if($finRcpt != null)
        {
            $qb->andwhere('d.date_reception <= :fin');
            $qb->setParameter('fin', $finRcpt);
        }
        if($debutTrt != null)
        {
            $qb->andwhere('d.date_trt >= :debut');
            $qb->setParameter('debut', $debutTrt);
        }
        if($finTrt != null)
        {
            $qb->andwhere('d.date_trt<= :fin');
            $qb->setParameter('fin', $finTrt);
        }
        if($type_jour_reception != null)
        {
            $qb->andWhere('DAYOFWEEK(d.date_reception) IN (:type_jour_reception)');
            $qb->setParameter('type_jour_reception', $type_jour_reception);
        }
        if($type_jour_traitement != null)
        {
            $qb->andWhere('DAYOFWEEK(d.date_trt) IN (:type_jour_traitement)');
            $qb->setParameter('type_jour_traitement', $type_jour_traitement);
        }
        if($estDemandeTraitee == 1)
            $qb->andWhere('d.date_trt IS NOT NULL');
        if($estDemandeTraitee == 0)
            $qb->andWhere('d.date_trt IS NULL');
        if($limit != null)
            $qb->setMaxResults( $limit );
        if($offset != null)
            $qb->setFirstResult( $offset );
        return $qb->getQuery()->getResult();
    }
    public function findEnCoursByDate($debut,$fin,$offset,$limit)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT p FROM AppBundle:Demande p WHERE p.date_reception >= :debut AND p.date_reception <= :fin AND p.date_trt IS NULL ORDER BY p.date_reception ASC'
            )
            ->setParameter('debut', $debut)
            ->setParameter('fin', $fin)
            ->setMaxResults($limit)
            ->setfirstResult($offset)
            ->getResult();
    }

    public function findTraiteesByDate($debut,$fin,$offset,$limit)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT p FROM AppBundle:Demande p WHERE p.date_reception >= :debut AND p.date_reception <= :fin AND p.date_trt IS NOT NULL ORDER BY p.date_reception ASC'
            )
            ->setParameter('debut', $debut)
            ->setParameter('fin', $fin)
            ->setMaxResults($limit)
            ->setfirstResult($offset)
            ->getResult();
    }

    public function findNbTaitees(\DateTime $day, $idPartenaire)
    {
        $dateMin = clone $day;
        $dateMin->setTime(0, 0, 0);
        $dateMax = clone $dateMin;
        $dateMax->add(new \DateInterval('P1D'));

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('count(d.num_demande)')
            ->where('d.date_trt > :dateMin')
            ->andWhere('d.date_trt < :dateMax')
            ->setParameter('dateMin', $dateMin)
            ->setParameter('dateMax', $dateMax);
        if($idPartenaire != 0){
            $qb ->from('AppBundle:Partenaire', 'p')
                ->join('p.moderateurs', 'm')
                ->join('AppBundle:Demande', 'd', 'WITH', 'd.moderateur = m.nom')
                ->andWhere('p.id = :idpartenaire')
                ->setParameter('idpartenaire', $idPartenaire);
        }else{
            $qb ->from('AppBundle:demande', 'd');
        }
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function findNbTaiteesAJ(\DateTime $day, $idPartenaire)
    {
        $dateMin = clone $day;
        $dateMin->setTime(0, 0, 0);
        $dateMax = clone $dateMin;
        $dateMax->add(new \DateInterval('P1D'));

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('count(d.num_demande)')
            ->where('d.date_trt >= :dateMin')
            ->andWhere('d.date_trt < :dateMax')
            ->andWhere('d.date_reception >= :dateMin')
            ->andWhere('d.date_reception < :dateMax')
            ->andWhere('d.moderateur is not NULL')
            ->setParameter('dateMin', $dateMin)
            ->setParameter('dateMax', $dateMax);
        if($idPartenaire != 0){
            $qb ->from('AppBundle:Partenaire', 'p')
                ->join('p.moderateurs', 'm')
                ->join('AppBundle:Demande', 'd', 'WITH', 'd.moderateur = m.nom')
                ->andWhere('p.id = :idpartenaire')
                ->setParameter('idpartenaire', $idPartenaire);
        }else{
            $qb ->from('AppBundle:demande', 'd');
        }
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function findNbTraiteesAuto(\DateTime $day)
    {
        $dateMin = clone $day;
        $dateMin->setTime(0, 0, 0);
        $dateMax = clone $dateMin;
        $dateMax->add(new \DateInterval('P1D'));

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('count(d.num_demande)')
            ->from('AppBundle:demande', 'd')
            ->where('d.date_trt > :dateMin')
            ->andWhere('d.date_trt < :dateMax')
            ->andWhere('d.date_reception > :dateMin')
            ->andWhere('d.date_reception < :dateMax')
            ->andWhere('d.moderateur is NULL')
            ->setParameter('dateMin', $dateMin)
            ->setParameter('dateMax', $dateMax);

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function findNbTaiteesModere(\DateTime $day, $idPartenaire)
    {
        $dateMin = clone $day;
        $dateMin->setTime(0, 0, 0);
        $dateMax = clone $dateMin;
        $dateMax->add(new \DateInterval('P1D'));

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('count(d.num_demande)')
            ->where('d.date_trt >= :dateMin')
            ->andWhere('d.date_trt < :dateMax')
            ->andWhere('d.moderateur is not NULL')
            ->setParameter('dateMin', $dateMin)
            ->setParameter('dateMax', $dateMax);
        if($idPartenaire != 0){
            $qb ->from('AppBundle:Partenaire', 'p')
                ->join('p.moderateurs', 'm')
                ->join('AppBundle:Demande', 'd', 'WITH', 'd.moderateur = m.nom')
                ->andWhere('p.id = :idpartenaire')
                ->setParameter('idpartenaire', $idPartenaire);
        }else{
            $qb ->from('AppBundle:demande', 'd');
        }
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function findNbArriveesModere(\DateTime $day, $idPartenaire)
    {
        $dateMin = clone $day;
        $dateMin->setTime(0, 0, 0);
        $dateMax = clone $dateMin;
        $dateMax->add(new \DateInterval('P1D'));

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('count(d.num_demande)')
            ->where('d.date_reception >= :dateMin')
            ->andWhere('d.date_reception < :dateMax')
            ->andWhere('d.moderateur is not NULL')
            ->setParameter('dateMin', $dateMin)
            ->setParameter('dateMax', $dateMax);
        if($idPartenaire != 0){
            $qb ->from('AppBundle:Partenaire', 'p')
                ->join('p.moderateurs', 'm')
                ->join('AppBundle:Demande', 'd', 'WITH', 'd.moderateur = m.nom')
                ->andWhere('p.id = :idpartenaire')
                ->setParameter('idpartenaire', $idPartenaire);
        }else{
            $qb ->from('AppBundle:demande', 'd');
        }
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function findNbEnCours(\DateTime $day, $idPartenaire)
    {
        $dateMin = clone $day;
        $dateMin->setTime(0, 0, 0);
        $dateMax = clone $dateMin;
        $dateMax->add(new \DateInterval('P1D'));

        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('count(d.num_demande)')
            ->from('AppBundle:demande', 'd')
            ->where('d.date_reception < :dateMax')
            ->andWhere('d.date_trt > :dateMax OR d.date_trt is NULL')
            ->setParameter('dateMax', $dateMax);
        if($idPartenaire != 0){
            $qb->leftjoin('AppBundle:moderateur', 'm', 'WITH', 'd.moderateur = m.nom')
                ->leftjoin('m.partenaires','p')
                ->andWhere('p.id = :idpartenaire')
                ->setParameter('idpartenaire', $idPartenaire);
        }

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function findNbEnCoursOlder48h(\DateTime $day, $idPartenaire)
    {
        $dateMin = clone $day;
        $dateMin->setTime(0, 0, 0);
        $dateMax = clone $dateMin;
        $dateMax->add(new \DateInterval('P1D'));
        $dateMin48 = clone $dateMin;
        $dateMin48->sub(new \DateInterval('P2D'));
        $dateMin48->setTime(0, 0, 0);

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('count(d.num_demande)')
            ->from('AppBundle:demande', 'd')
            ->where('d.date_reception < :dateMin48')
            ->andWhere('d.date_trt > :dateMax OR d.date_trt is NULL')
            ->setParameter('dateMax', $dateMax)
            ->setParameter('dateMin48', $dateMin48);
        if($idPartenaire != 0){
            $qb->leftjoin('AppBundle:moderateur', 'm', 'WITH', 'd.moderateur = m.nom')
                ->leftjoin('m.partenaires','p')
                ->andWhere('p.id = :idpartenaire')
                ->setParameter('idpartenaire', $idPartenaire);
        }
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function findNbArrivees(\DateTime $day)
    {
        $dateMin = clone $day;
        $dateMin->setTime(0, 0, 0);
        $dateMax = clone $dateMin;
        $dateMax->add(new \DateInterval('P1D'));

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('count(d.num_demande)')
            ->from('AppBundle:demande', 'd')
            ->where('d.date_reception >= :dateMin')
            ->andWhere('d.date_reception < :dateMax')
            ->setParameter('dateMin', $dateMin)
            ->setParameter('dateMax', $dateMax);
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function findOlderEnCours(\DateTime $day, $idPartenaire)
    {
        $dateMin = clone $day;
        $dateMin->setTime(0, 0, 0);
        $dateMax = clone $dateMin;
        $dateMax->add(new \DateInterval('P1D'));

        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('d.date_reception')
            ->from('AppBundle:demande', 'd')
            ->where('d.date_trt >= :dateMax OR d.date_trt is NULL')
            ->setParameter('dateMax', $dateMax)
            ->orderBy('d.date_reception', 'ASC');
        if($idPartenaire != 0){
            $qb->leftjoin('AppBundle:moderateur', 'm', 'WITH', 'd.moderateur = m.nom')
                ->leftjoin('m.partenaires','p')
                ->andWhere('p.id = :idpartenaire')
                ->setParameter('idpartenaire', $idPartenaire);
        }
        $date = $qb->getQuery()->getResult()[0]['date_reception'];


//        $d = \DateTime::createFromFormat('Y-m-d', explode(' ',$date[0]['d'])[0]);
        setlocale(LC_TIME, 'fr_FR.utf8','fra');
        return strftime("%a %d/%m/%Y", $date->getTimestamp());
    }

    public function averageDeltaTraitement(\DateTime $day, $idPartenaire)
    {
//        $dateMax = clone $day;
//        $dateMax->setTime(23, 59, 59);
//        $dateMin = clone $dateMax;
//        $dateMin->sub(new \DateInterval('P7D'));
//
//
//        $qb = $this->getEntityManager()->createQueryBuilder();
//        $qb->select('avg(d.date_trt - d.date_reception)')
//            ->where('d.date_trt <= :dateMax')
//            ->andWhere('d.date_trt >= :dateMin')
//            ->andWhere('d.moderateur is not NULL')
//            ->setParameter('dateMin', $dateMin)
//            ->setParameter('dateMax', $dateMax);
//        if($idPartenaire != 0){
//            $qb ->from('AppBundle:Partenaire', 'p')
//                ->join('p.moderateurs', 'm')
//                ->join('AppBundle:Demande', 'd', 'WITH', 'd.moderateur = m.nom')
//                ->andWhere('p.id = :idpartenaire')
//                ->setParameter('idpartenaire', $idPartenaire);
//        }else{
//            $qb ->from('AppBundle:demande', 'd');
//        }
//        return explode('.',$qb->getQuery()->getSingleScalarResult())[0];

//        $dateMax = clone $day;
//        $dateMax->setTime(23, 59, 59);
//        $dateMin = clone $dateMax;
//        $dateMin->sub(new \DateInterval('P7D'));
//
//        $qb = $this->getEntityManager()->createQueryBuilder();
//        $qb->select('d.date_trt - d.date_reception as diff')
//            ->from('AppBundle:demande', 'd')
//            ->where('d.date_trt >= :dateMin')
//            ->setParameter('dateMin', $dateMin)
//            ->andWhere('d.date_trt <= :dateMax')
//            ->setParameter('dateMax', $dateMax)
//            ->andWhere('d.moderateur is not NULL');
//
//        $qb2 = $this->getEntityManager()->createQueryBuilder();
//        $qb2->select('avg(a.diff)')
//            ->from($qb->getDQL(),'a');
//
//        $res = $qb2->getQuery()->getResult();
//
//        var_dump($res);
//
//        return $res;

        $dateMax = clone $day;
        $dateMax->setTime(23, 59, 59);
        $dateMin = clone $dateMax;
        $dateMin->sub(new \DateInterval('P7D'));

        $join = '';
        $where = '';
        if($idPartenaire != '0'){
            $join = ' left join moderateur m on d.moderateur = m.nom ';
            $join .= ' left join moderateur_partenaire mp on m.id = mp.moderateur_id ';
            $where = ' and mp.partenaire_id = \''.$idPartenaire.'\'';
        }

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('justify_interval', 'avg');

        $avg = $this->getEntityManager()
            ->createNativeQuery('
                SELECT justify_interval(avg(a.diff))
                FROM (SELECT (date_trt - date_reception) as diff
                      FROM public.demande d '.$join.'
                      WHERE d.date_trt::date >= \''.$dateMin->format('Y-m-d').'\'
                        AND d.date_trt::date <= \''.$dateMax->format('Y-m-d').'\'
                        AND d.moderateur is not NULL '.$where.'
                      ) as a',
                $rsm)
            ->getResult();

        return $this->FormatEnglishIntervalToFrench(explode('.',$avg[0]['avg'])[0]);
    }

    public function averageDeltaDelaiTraitement(\DateTime $day, $idPartenaire)
    {
        $dateMax = clone $day;
        $dateMax->setTime(23, 59, 59);
        $dateMin = clone $dateMax;
        $dateMin->sub(new \DateInterval('P7D'));

        $join = '';
        $andWhere = '';
        $orWhere = '';
        if($idPartenaire != '0'){
            $join = ' left join moderateur m on d.moderateur = m.nom ';
            $join .= ' left join moderateur_partenaire mp on m.id = mp.moderateur_id ';
            $andWhere = ' and mp.partenaire_id = \''.$idPartenaire.'\'';
            $orWhere = ' or mp.partenaire_id = \''.$idPartenaire.'\'';
        }

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('justify_interval', 'avg');


        $avg = $this->getEntityManager()
            ->createNativeQuery('
                SELECT justify_interval(avg(a.diff))
                FROM (SELECT (date_trt - date_reception) as diff
                      FROM public.demande d '.$join.'
                      WHERE d.date_trt::date >= \''.$dateMin->format('Y-m-d').'\'
                        AND d.date_trt::date <= \''.$dateMax->format('Y-m-d').'\'
                        AND d.moderateur is not NULL '.$andWhere.'
                      UNION
                      SELECT (now() - date_reception) as diff
                      FROM public.demande d '.$join.'
                      WHERE (d.date_trt::date > \''.$dateMax->format('Y-m-d').'\' OR d.date_trt is NULL)
                        AND d.date_reception::date < \''.$dateMax->format('Y-m-d').'\'
                        AND (d.moderateur is NULL '.$orWhere.')
                      ) as a',
                $rsm)
            ->getResult();

        return $this->FormatEnglishIntervalToFrench(explode('.',$avg[0]['avg'])[0]);
    }

    public function createChartLabels(\DateTime $day, $nbJours)
    {
        $labels = [];
        $cptJours = 0;

        $previous_day = clone $day;

        while ($cptJours < $nbJours) {
            // substract 1 day
            $previous_day->sub(new \DateInterval('P1D'));
            setlocale(LC_TIME, 'fr_FR.utf8','fra');
//            array_push($labels, $previous_day->format('l d/m/Y'));
            array_push($labels, strftime("%a %d/%m/%Y", $previous_day->getTimestamp()));
            $cptJours = $cptJours + 1;
        }
        $labels = array_reverse($labels);
        return $labels;
    }

    public function findChartDatasEnCours(\DateTime $day, $nbJours, $idPartenaire)
    {
        $data = [];
        $cptJours = 0;

        $dateMin = clone $day;
        $dateMin->setTime(0, 0, 0);
        $dateMax = clone $dateMin;
        $dateMax->add(new \DateInterval('P1D'));

        $previous_day = clone $day;

        while ($cptJours < $nbJours) {
            // substract 1 day
            $dateMin->sub(new \DateInterval('P1D'));
            $dateMax->sub(new \DateInterval('P1D'));

//            $rsm = new ResultSetMapping();
//            $rsm->addScalarResult('count', 'count');
//            $count = $this->getEntityManager()
//                ->createNativeQuery(
//                    'SELECT count(num_demande)
//                    FROM public.Demande p
//                    WHERE date_reception::date <= \''.$previous_day->format('Y-m-d').'\'
//                    AND (date_trt::date > \''.$previous_day->format('Y-m-d').'\'
//                    OR date_trt::date IS NULL)
//                    AND moderateur is not NULL',
//                    $rsm
//                )
//                ->getResult();

            $qb = $this->getEntityManager()->createQueryBuilder();
            $qb->select('count(d.num_demande)')
                ->from('AppBundle:demande', 'd')
                ->where('d.date_reception < :dateMax')
                ->andWhere('d.date_trt >= :dateMax OR d.date_trt is NULL')
                ->andWhere('d.moderateur is not NULL')
                ->setParameter('dateMax', $dateMax);
            if($idPartenaire != 0){
                $qb->leftjoin('AppBundle:moderateur', 'm', 'WITH', 'd.moderateur = m.nom')
                    ->leftjoin('m.partenaires','p')
                    ->andWhere('p.id = :idpartenaire')
                    ->setParameter('idpartenaire', $idPartenaire);
            }
            $count = $qb->getQuery()->getSingleScalarResult();


            array_push($data, $count);
            $cptJours = $cptJours + 1;
        }
        $data = array_reverse($data);

        return $data;
    }

    public function findChartDatasTraitees(\DateTime $day, $nbJours, $idPartenaire)
    {
        $data = [];
        $cptJours = 0;

        $dateMin = clone $day;
        $dateMin->setTime(0, 0, 0);
        $dateMax = clone $dateMin;
        $dateMax->add(new \DateInterval('P1D'));

        $previous_day = clone $day;

        while ($cptJours < $nbJours) {
            // substract 1 day
            $dateMin->sub(new \DateInterval('P1D'));
            $dateMax->sub(new \DateInterval('P1D'));

            $qb = $this->getEntityManager()->createQueryBuilder();
            $qb->select('count(d.num_demande)')
                ->from('AppBundle:demande', 'd')
                ->where('d.date_trt < :dateMax')
                ->andWhere('d.date_trt >= :dateMin')
                ->andWhere('d.moderateur is not NULL')
                ->setParameter('dateMax', $dateMax)
                ->setParameter('dateMin', $dateMin);
            if($idPartenaire != 0){
                $qb->leftjoin('AppBundle:moderateur', 'm', 'WITH', 'd.moderateur = m.nom')
                    ->leftjoin('m.partenaires','p')
                    ->andWhere('p.id = :idpartenaire')
                    ->setParameter('idpartenaire', $idPartenaire);
            }
            $count = $qb->getQuery()->getSingleScalarResult();


//            $rsm = new ResultSetMapping();
//            $rsm->addScalarResult('count', 'count');
//            $count = $this->getEntityManager()
//                ->createNativeQuery(
//                    'SELECT count(num_demande)
//                    FROM public.Demande p
//                    WHERE date_trt::date = \''.$previous_day->format('Y-m-d').'\'
//                    AND moderateur is not NULL',
//                    $rsm
//                )
//                ->getResult();

            array_push($data, $count);
            $cptJours = $cptJours + 1;
        }
        $data = array_reverse($data);

        return $data;
    }

    public function findChartDatasRatio(\DateTime $day, $nbJours, $idPartenaire)
    {
        $data = [];
        $cptJours = 0;

        $dateMin = clone $day;
        $dateMin->setTime(0, 0, 0);
        $dateMax = clone $dateMin;
        $dateMax->add(new \DateInterval('P1D'));

        $previous_day = clone $day;

        while ($cptJours < $nbJours) {
            // substract 1 day

            $dateMin->sub(new \DateInterval('P1D'));
            $dateMax->sub(new \DateInterval('P1D'));

            $qb = $this->getEntityManager()->createQueryBuilder();
            $qb->select('count(d.num_demande)')
                ->from('AppBundle:demande', 'd')
                ->where('d.date_reception < :dateMax')
                ->andWhere('d.date_reception >= :dateMin')
                ->andWhere('d.moderateur is not NULL')
                ->setParameter('dateMax', $dateMax)
                ->setParameter('dateMin', $dateMin);
            if($idPartenaire != 0){
                $qb->leftjoin('AppBundle:moderateur', 'm', 'WITH', 'd.moderateur = m.nom')
                    ->leftjoin('m.partenaires','p')
                    ->andWhere('p.id = :idpartenaire')
                    ->setParameter('idpartenaire', $idPartenaire);
            }
            $countRecu = $qb->getQuery()->getSingleScalarResult();

            $qb = $this->getEntityManager()->createQueryBuilder();
            $qb->select('count(d.num_demande)')
                ->from('AppBundle:demande', 'd')
                ->where('d.date_trt < :dateMax')
                ->andWhere('d.date_trt >= :dateMin')
                ->andWhere('d.date_reception < :dateMax')
                ->andWhere('d.date_reception >= :dateMin')
                ->andWhere('d.moderateur is not NULL')
                ->setParameter('dateMax', $dateMax)
                ->setParameter('dateMin', $dateMin);
            if($idPartenaire != 0){
                $qb->leftjoin('AppBundle:moderateur', 'm', 'WITH', 'd.moderateur = m.nom')
                    ->leftjoin('m.partenaires','p')
                    ->andWhere('p.id = :idpartenaire')
                    ->setParameter('idpartenaire', $idPartenaire);
            }
            $countTraitees = $qb->getQuery()->getSingleScalarResult();

            if ($countRecu > 0) {
                array_push($data, number_format($countTraitees * 100 / $countRecu, 2));
            }else {
                array_push($data, number_format(100, 2));
            }
            $cptJours = $cptJours + 1;
        }
        $data = array_reverse($data);

        return $data;
    }

    private function FormatEnglishIntervalToFrench($interval)
    {
        if($interval != null && $interval != '') {
            $interval = str_replace(' days', 'J', $interval);
            $interval = str_replace(' day', 'J', $interval);
            $intervalArray = explode(':', $interval);
            $interval = $intervalArray[0] . 'h ' . $intervalArray[1] . 'min';
        }else{
            $interval = '';
        }
        return $interval;
    }

}
