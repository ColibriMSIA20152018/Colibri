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
use \Symfony\Component\Form\Extension\Core\Type\IntegerType;
use \Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use \Symfony\Bridge\Doctrine\Form\Type\EntityType;


class AdminController extends Controller
{
    public function indexAction(Request $request)
    {

        return $this->render('admin.html.twig',array());
    }
    /****************************
     ******* PARTIE PANIER  *****
     ****************************/
    public function ajouterProduitPanierAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        
        $form = $this->get('form.factory')->createNamedBuilder('formulaire_ajout_produit_panier')
            ->add('produit', EntityType::class, array('class' => 'AMAPBundle:Produit', 'choice_label' => 'libelle') )
            ->add('panier', EntityType::class, array('class' => 'AMAPBundle:Panier', 'choice_label' => 'libelle') )
            ->add('quantite', IntegerType::class)
            ->add('ajouter', SubmitType::class, array('label' => 'Ajouter un produit à un Panier'))
            ->getForm();
        
        if ($form->handleRequest($request)->isSubmitted())
        {
            if ($form->get('ajouter')->isClicked())
            {
                $data = $form->getData();

                $panierproduit = new PanierProduit();


                $panierproduit->setProduit($data['produit']);
                $panierproduit->setQuantite($data['quantite']);

                $panier = $data['panier']->addPanierproduit($panierproduit);

                $panierproduit->setPanier($panier);

                $em->persist($panier);
                $em->persist($panierproduit);
                $em->flush();
            }
        }
        
        return $this->render('AMAPBundle:Admin/Panier:ajouterProduit.html.twig',array('form'=>$form->createView()));        
    }
    
    public function creerPanierAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $form = $this->get('form.factory')->createNamedBuilder('formulaire_creation_panier')
            ->add('libelle', TextType::class )
            ->add('saison', EntityType::class,array('class' => 'AMAPBundle:Saison', 'choice_label' => 'libelle'))
            ->add('type_panier', EntityType::class,array('class' => 'AMAPBundle:TypePanier', 'choice_label' => 'libelle'))
            ->add('prix', IntegerType::class)
            ->add('ajouter', SubmitType::class, array('label' => 'Créer le panier'))
            ->getForm();


         $form2 = $this->createFormBuilder()
            ->add('libelle', TextType::class )
            ->add('ajouter', SubmitType::class, array('label' => 'Créer Type Panier'))
            ->getForm();
         
         if ($form->handleRequest($request)->isSubmitted() ||
                $form2->handleRequest($request)->isSubmitted())
         {
            if ($form->get('ajouter')->isClicked())
            {
                $data = $form->getData();

                $panier = new Panier();

                $panier->setLibelle($data['libelle']);
                $panier->setSaison($data['saison']);
                $panier->setPrix($data['prix']);

                $em->persist($panier);
                $em->flush();

                //return $this->redirect($this->generateUrl('amap_panier_ajouter'));
            }

            if ($form2->get('ajouter')->isClicked())
            {
                $data = $form2->getData();

                $typePanier = new TypePanier();

                $typePanier->setLibelle($data['libelle']);

                $em->persist($typePanier);
                $em->flush();

                //return $this->redirect($this->generateUrl('amap_panier_ajouter'));
            }
         }
         
        return $this->render('AMAPBundle:Admin/Panier:creer.html.twig',array('form'=>$form->createView(),
                                                       'form2'=>$form2->createView()));
    }
    
    public function retraitPanierAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        
        $form = $this->get('form.factory')->createNamedBuilder('formulaire_retirer_panier')
            ->add('panier', EntityType::class, array('class' => 'AMAPBundle:Panier', 'choice_label' => 'libelle') )
	    ->add('amap', EntityType::class, array('class' => 'AMAPBundle:Amap', 'choice_label' => 'libelle'))
            ->add('ajouter', SubmitType::class, array('label' => 'Retirer un Panier'))
            ->getForm();
        
        return $this->render('AMAPBundle:Admin/Panier:retrait.html.twig',array('form'=>$form->createView()));
    }
    
    /*******************************
     ********* FIN PANIER***********
     *******************************/
    
    /*******************************
     ********* DEBUT PRODUIT********
     *******************************/
    
    public function listerProduitAction(){
        $em = $this->getDoctrine()->getManager();
        
        $listProduit = $em->getRepository('AMAPBundle:Produit')->findAll();
        
        return $this->render('AMAPBundle:Admin/Produit:listProduit.html.twig',array('listProduit'=>$listProduit));
    }
    
    public function ajouterProduitAction(Request $request){
        $em = $this->getDoctrine()->getManager();

        /* Création du formulaire pour ajouter un nouveau type de produit */
        $form = $this->get('form.factory')->createNamedBuilder('formulaire_produit')
            ->setAttributes(array('name' => 'formulaire'))
            ->add('libelle', TextType::class)
            ->add('famille', EntityType::class,array('class' => 'AMAPBundle:Famille', 'choice_label' => 'libelle'))
            ->add('ajouter', SubmitType::class, array('label' => 'Ajouter un produit'))
            ->getForm();
        
        if ($form->handleRequest($request)->isSubmitted()){

            /* Gestion du formulaire pour ajouter un nouveau type de produit */
           if ($form->get('ajouter')->isClicked())
           {
                $data = $form->getData();

                $produit = new Produit();

                $produit->setLibelle($data['libelle']);
                $produit->setFamille($data['famille']);

                $em->persist($produit);
                $em->flush();
           }
        }
        
        return $this->render('AMAPBundle:Admin/Produit:ajouterProduit.html.twig',array('form'=>$form->createView()));
    }
    
    public function creerSaisonAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        
        $listSaison = $em->getRepository('AMAPBundle:Saison')->findAll();
        
        $form = $this->get('form.factory')->createNamedBuilder('formulaire_creation_saison')
            ->add('libelle', TextType::class)
            ->add('ajouter', SubmitType::class, array('label' => 'Créer la saison'))
            ->getForm();
        if ($form->handleRequest($request)->isSubmitted()){
            if ($form->get('ajouter')->isClicked())
            {
                $data = $form->getData();

                $saison = new Saison();

                $saison->setLibelle($data['libelle']);

                $em->persist($saison);
                $em->flush();
            }
        }
        
        return $this->render('AMAPBundle:Admin/Produit:creerSaison.html.twig',array('form'=>$form->createView(),'listSaison'=>$listSaison));
    }
    
    public function creerFamilleAction(Request $request){
        
        $em = $this->getDoctrine()->getManager();
        
        $listFamille = $em->getRepository('AMAPBundle:Famille')->findAll();
        
         $form = $this->get('form.factory')->createNamedBuilder('formulaire_creation_famille')
            ->add('libelle', TextType::class)
            ->add('ajouter', SubmitType::class, array('label' => 'Créer la famille'))
            ->getForm();
        
        if ($form->handleRequest($request)->isSubmitted())
        {
           if ($form->get('ajouter')->isClicked())
           {
                $data = $form->getData();

                $famille = new Famille();

                $famille->setLibelle($data['libelle']);

                $em->persist($famille);
                $em->flush();
           }
        }
        
        return $this->render('AMAPBundle:Admin/Produit:creerFamille.html.twig',array('form'=>$form->createView(),'listFamille'=>$listFamille));
    }
}