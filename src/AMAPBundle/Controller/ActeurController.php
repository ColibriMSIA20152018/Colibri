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
            ->add('typeActeur',EntityType::class,array('class' => 'AMAPBundle:TypeActeur', 'choice_label' => 'libelle'))
            ->add('nom', TextType::class )
            ->add('prenom', TextType::class )
            ->add('dateNaissance', DateType::class, array('input'=>'datetime','years' => range(1900, date('Y'))))
            ->add('ajouter', SubmitType::class, array('label' => 'Créer acteur'))
            ->getForm();
        
        $form2 = $this->get('form.factory')->createNamedBuilder('formulaire_creation_type_acteur')
            ->add('libelle', TextType::class )
            ->add('ajouter', SubmitType::class, array('label' => 'Créer type acteur'))
            ->getForm();
        
        if ($form->handleRequest($request)->isSubmitted() || 
            $form2->handleRequest($request)->isSubmitted()){ 
           if ($form->get('ajouter')->isClicked())
           {
                $data = $form->getData(); 
                
                $acteur = new Acteur();
                
                $acteur->setTypeActeur($data['typeActeur']);
                $acteur->setNom($data['nom']);
                $acteur->setPrenom($data['prenom']);                
                $acteur->setDateNaissance($data['dateNaissance']);
             
                $em->persist($acteur);
                $em->flush();

                //return $this->redirect($this->generateUrl('amap_panier_ajouter'));
           }
           if ($form2->get('ajouter')->isClicked())
           {
               $data2 = $form2->getData();
               
               $typeActeur = new TypeActeur();
               
               $typeActeur->setLibelle($data2['libelle']);
               
               $em->persist($typeActeur);
               $em->flush();   
           }
        }
        
        $listacteur = $em->getRepository('AMAPBundle:Acteur')->findAll();
        
        return $this->render('AMAPBundle:Acteur:index.html.twig',array(
            'form' => $form->createView(),
            'form2' => $form2->createView(),
            'page_courante' => 'acteur',
            'listacteur' => $listacteur));
    }
}