<?php

namespace AMAPBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use \AMAPBundle\Entity\Panier;
use \AMAPBundle\Entity\TypePanier;
use \AMAPBundle\Entity\PanierProduit;
use \AMAPBundle\Entity\Produit;
use \AMAPBundle\Entity\Saison;
use \AMAPBundle\Entity\Stock;
use AMAPBundle\Entity\Inscription;
use \Symfony\Component\Form\Extension\Core\Type\IntegerType;
use \Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use \Symfony\Bridge\Doctrine\Form\Type\EntityType;


class PanierController extends Controller
{
    public function consulterAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $session = $request->getSession();
        
        $paniers = $em->getRepository('AMAPBundle:Panier')->findBy(array('amap'=>$session->get('amap')));
        
        return $this->render('AMAPBundle:Panier:consulterPaniers.html.twig',array('paniers'=>$paniers,
                                                                                    'page_courante'=>'panier'));
    }
    
    public function inscriptionPanierAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        
        $panier = $em->getRepository('AMAPBundle:Panier')->find($id);
        
        return $this->render('AMAPBundle:Panier:inscription.html.twig', array('panier'=>$panier));
    }
    
    public function validerInscriptionAction(Request $request, $id)
    {
        
        $em = $this->getDoctrine()->getManager();
        
        $session =  $request->getSession();
        
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        
        $inscription = new Inscription();
        
        $inscription->setActeur($user);
        
        $amap = $em->getRepository('AMAPBundle:Amap')->find($session->get('amap'));
        $panier = $em->getRepository('AMAPBundle:Panier')->find($id);
        $inscription->setAmap($amap);
        $inscription->setPanier($panier);
        
        $em->persist($inscription);
        $em->flush();
        
        return $this->redirect($this->generateUrl('amap_homepage'));
        
    }
    
    public function ajouterMessageAction()
    {
        return $this->render('AMAPBundle:Panier:messageAjouter.html.twig');
    }

}