<?php

namespace AMAPBundle\Repository;

/**
 * ContratRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ContratRepository extends \Doctrine\ORM\EntityRepository
{
    function getContrats($id){
        return $this->getEntityManager()->createQueryBuilder('c')->select('c')->where('c.amap = ?1')->from('AMAPBundle:Contrat','c')->setParameter(1,$id);
    } 
}
