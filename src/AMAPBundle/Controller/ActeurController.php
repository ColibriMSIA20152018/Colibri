<?php

namespace AMAPBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use \AMAPBundle\Entity\Panier;
use \AMAPBundle\Entity\PanierProduit;
use \AMAPBundle\Entity\Produit;
use \AMAPBundle\Entity\Saison;
use \AMAPBundle\Entity\Acteur;
use \Symfony\Component\Form\Extension\Core\Type\IntegerType;
use \Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use \Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class ActeurController extends Controller
{
    public function ajouterAction(Request $request)
    {       
        $em = $this->getDoctrine()->getManager();

        $form = $this->get('form.factory')->createNamedBuilder('formulaire_creation_acteur')
            ->add('nom', TextType::class )
            ->add('prenom', TextType::class )
            ->add('dateNaissance', DateType::class, array('input'=>'datetime','years' => range(1900, date('Y'))))
            ->add('ajouter', SubmitType::class, array('label' => 'Créer acteur'))
            ->getForm();
        
        if ($form->handleRequest($request)->isSubmitted() ){ 
           if ($form->get('ajouter')->isClicked())
           {
                $data = $form->getData(); 
                
                $acteur = new Acteur();
                
                $acteur->setNom($data['nom']);
                $acteur->setPrenom($data['prenom']);                
                $acteur->setDateNaissance($data['dateNaissance']);
             
                $em->persist($acteur);
                $em->flush();
                
               
                //return $this->redirect($this->generateUrl('amap_panier_ajouter'));
           }
        }
        
        $listacteur = $em->getRepository('AMAPBundle:Acteur')->findAll();
        
        return $this->render('AMAPBundle:Acteur:index.html.twig',array(
            'form' => $form->createView(),
            'page_courante' => 'acteur',
            'listacteur' => $listacteur));
    }
}