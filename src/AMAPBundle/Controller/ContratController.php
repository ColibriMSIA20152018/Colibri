<?php

namespace AMAPBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use \AMAPBundle\Entity\Panier;
use \AMAPBundle\Entity\PanierProduit;
use \AMAPBundle\Entity\Produit;
use \AMAPBundle\Entity\Saison;
use \AMAPBundle\Entity\Acteur;
use \AMAPBundle\Entity\TypeActeur;
use \AMAPBundle\Entity\Contrat;
use \Symfony\Component\Form\Extension\Core\Type\IntegerType;
use \Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use \Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class ContratController extends Controller
{
    public function creerAction(Request $request)
    {       
        $em = $this->getDoctrine()->getManager();

        $form = $this->get('form.factory')->createNamedBuilder('formulaire_creer_contrat')
            ->add('producteur',EntityType::class,array('class' => 'AMAPBundle:Acteur', 'choice_label' => 'nom'))
            ->add('consommateur',EntityType::class,array('class' => 'AMAPBundle:Acteur', 'choice_label' => 'nom'))
            ->add('panier',EntityType::class,array('class' => 'AMAPBundle:Panier', 'choice_label' => 'libelle'))
            ->add('ajouter', SubmitType::class, array('label' => 'Créer contrat'))
            ->getForm();
        
        if ($form->handleRequest($request)->isSubmitted()){ 
           if ($form->get('ajouter')->isClicked())
           {
                $data = $form->getData(); 
                
                $contrat = new Contrat();
                
                $contrat->setProducteur($data['producteur']);
                $contrat->setConsommateur($data['consommateur']);
                $contrat->setPanier($data['panier']);                
             
                $em->persist($contrat);
                $em->flush();

                //return $this->redirect($this->generateUrl('amap_panier_ajouter'));
           }
        }
        
        $listcontrat = $em->getRepository('AMAPBundle:Contrat')->findAll();
        
        return $this->render('AMAPBundle:Contrat:index.html.twig',array(
            'form' => $form->createView(),
            'page_courante' => 'contrat',
            'listcontrat' => $listcontrat));
    }
}