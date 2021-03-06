<?php

namespace AMAPBundle\Repository;

/**
 * PanierRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PanierRepository extends \Doctrine\ORM\EntityRepository
{
    function getAmap($amap){
        return $this->getEntityManager()->createQueryBuilder('p')->select('p')->where('p.amap = ?1')->from('AMAPBundle:Panier','p')->setParameter(1,$amap);
    } 
    
    function getPanier($id){
        return $this->getEntityManager()->createQueryBuilder('p')->select('p')->where('p.id = ?1')->from('AMAPBundle:Panier','p')->setParameter(1,$id);
    } 
}
