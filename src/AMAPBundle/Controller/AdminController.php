<?php

namespace AMAPBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use \AMAPBundle\Entity\Panier;
use \AMAPBundle\Entity\PanierProduit;
use \AMAPBundle\Entity\Produit;
use \AMAPBundle\Entity\Saison;
use \AMAPBundle\Entity\Stock;
use \AMAPBundle\Entity\Livraison;
use \AMAPBundle\Entity\Entrepot;
use \AMAPBundle\Entity\Amap;
use \AMAPBundle\Entity\Acteur;
use \AMAPBundle\Entity\Famille;
use \AMAPBundle\Entity\Contrat;
use \AMAPBundle\Entity\Adresse;
use \AMAPBundle\Entity\TypeActeur;
use \Symfony\Component\Form\Extension\Core\Type\IntegerType;
use \Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use \Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use AMAPBundle\Entity\TypePanier;

class AdminController extends Controller
{
    public function indexAction(Request $request)
    {        
        $em = $this->getDoctrine()->getManager();

        $form = $this->get('form.factory')->createNamedBuilder('formulaire_select_amap')
            ->add('amap',EntityType::class,array('class' => 'AMAPBundle:Amap', 'choice_label' => 'libelle'))
            ->add('ajouter', SubmitType::class, array('label' => 'Selection d\'une AMAP'))
            ->getForm();

        if ($form->handleRequest($request)->isSubmitted()){
            
            $data = $form->getData();
            
            $session =  $request->getSession();

            $session->set('amap',$data['amap']->getId());
            $session->set('amap_libelle',$data['amap']->getLibelle());
            
            return $this->redirectToRoute('amap_admin');
	}
        
        return $this->render('AMAPBundle:Admin/Default:selectAmap.html.twig',array('page_courante' => 'AdminAccueil',
            'onglet_courant' => 'aucun',
            'form' => $form->createView()));
    }
    
    
    /*****************************
     **** DEBUT INSCRIPTION  *****
     ****************************/
    
    public function inscriptionAction(Request $request)
    {
        
        $em = $this->getDoctrine()->getManager();
        
        $session = $request->getSession();
        
        $inscription = $em->getRepository('AMAPBundle:Inscription')->findBy(array('amap'=>$session->get('amap')));
        
        return $this->render('AMAPBundle:Admin/Inscription:listerInscription.html.twig',array('page_courante' => 'AdminAccueil', 
                                                                                                'onglet_courant' => 'aucun',
                                                                                                'inscriptions'=>$inscription));
    }
    
    /*****************************
     ****** FIN INSCRIPTION  *****
     ****************************/
    
    /*****************************
     ******* DEBUT PANIER  *******
     ****************************/
    public function ajouterProduitPanierAction(Request $request){
        $em = $this->getDoctrine()->getManager();

        $session = $request->getSession();
        
        $form = $this->get('form.factory')->createNamedBuilder('formulaire_ajout_produit_panier')
            ->add('produit', EntityType::class, array('class' => 'AMAPBundle:Produit', 'choice_label' => 'libelle') )
            ->add('panier', EntityType::class, array('class' => 'AMAPBundle:Panier',
                'choice_label' => 'libelle',
                'query_builder' => $this->getDoctrine()->getRepository('AMAPBundle:Panier')->getAmap($session->get('amap'))))
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
        
        return $this->render('AMAPBundle:Admin/Panier:ajouterProduit.html.twig',array('page_courante' => 'AdminPanier', 'onglet_courant' => 'ajouterProduitPanierAction','form'=>$form->createView()));        
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
                $session =  $request->getSession();
                
                $data = $form->getData();

                $panier = new Panier();

                $panier->setLibelle($data['libelle']);
                $panier->setSaison($data['saison']);
                $panier->setTypePanier($data['type_panier']);
                $panier->setPrix($data['prix']);
                $amap = $em->getRepository('AMAPBundle:Amap')->findOneBy(array('id' => $session->get('amap')));
                $panier->setAmap($amap);

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
         
        return $this->render('AMAPBundle:Admin/Panier:creer.html.twig',array('page_courante' => 'AdminPanier', 'onglet_courant' => 'creerPanierAction',
                                                        'form'=>$form->createView(),
                                                       'form2'=>$form2->createView()));
    }
    
    public function retraitPanierAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $form = $this->get('form.factory')->createNamedBuilder('formulaire_retirer_panier')
            ->add('panier', EntityType::class, array('class' => 'AMAPBundle:Panier', 'choice_label' => 'libelle') )
	    ->add('amap', EntityType::class, array('class' => 'AMAPBundle:Amap', 'choice_label' => 'libelle'))
            ->add('ajouter', SubmitType::class, array('label' => 'Retirer un Panier'))
            ->getForm();
        
        if($form->handleRequest($request)->isSubmitted()){
            if ($form->get('ajouter')->isClicked())
            {               
 
                $data = $form->getData();                                
                $panier = $data['panier'];
               
                $panierproduit = $panier->getPanierproduit();
              
                $entrepot = $em->getRepository('AMAPBundle:Entrepot')->findOneBy(array('amap'=>$request->getSession()->get('amap')));
                var_dump('ici avant for');
                foreach ($panierproduit as $produit) {

                    $stock = $em->getRepository('AMAPBundle:Stock')->findBy(array('produit' => $produit->getProduit(), 'entrepot' => $entrepot));
                    var_dump('ici');
                    $stock[0]->setQuantite($stock[0]->getQuantite() - $produit->getQuantite());

                    $em->persist($stock[0]);         
                }

                $em->flush();

                 //return $this->redirect($this->generateUrl('amap_panier_ajouter'));
            }
        }
        return $this->render('AMAPBundle:Admin/Panier:retrait.html.twig',array('page_courante' => 'AdminPanier', 'onglet_courant' => 'retraitPanierAction','form'=>$form->createView()));
    }
    
    public function consulterAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $session = $request->getSession();
        
        $paniers = $em->getRepository('AMAPBundle:Panier')->findBy(array('amap' => $session->get('amap')));
        
        return $this->render('AMAPBundle:Admin/Panier:consulterPaniers.html.twig',array('page_courante' => 'AdminPanier', 'onglet_courant' => 'consulterAction','paniers'=>$paniers));
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
        
        return $this->render('AMAPBundle:Admin/Produit:listProduit.html.twig',array('page_courante' => 'AdminProduits', 'onglet_courant' => 'listerProduitAction', 'listProduit'=>$listProduit));
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
        
        return $this->render('AMAPBundle:Admin/Produit:ajouterProduit.html.twig',array('page_courante' => 'AdminProduits', 'onglet_courant' => 'ajouterProduitAction','form'=>$form->createView()));
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
        
        return $this->render('AMAPBundle:Admin/Produit:creerSaison.html.twig',array('page_courante' => 'AdminProduits', 'onglet_courant' => 'creerSaisonAction','form'=>$form->createView(),'listSaison'=>$listSaison));
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
        
        return $this->render('AMAPBundle:Admin/Produit:creerFamille.html.twig',array('page_courante' => 'AdminProduits', 'onglet_courant' => 'creerFamilleAction','form'=>$form->createView(),'listFamille'=>$listFamille));
    }
    /********************************
     ********* FIN PRODUIT **********
     *******************************/
    
    /********************************
     ********* DEBUT ACTEUR *********
     *******************************/
    
    public function listerActeurAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $session =  $request->getSession();
       
        $typeProd = $em->getRepository('AMAPBundle:TypeActeur')->findBy(array('libelle' => "Producteur"));
	$typeAd =$em->getRepository('AMAPBundle:TypeActeur')->findBy(array('libelle' => "Consommateur"));
	$listProd = $em->getRepository('AMAPBundle:Acteur')->findBy(array('amap' => $session->get('amap'), 'typeActeur' => $typeProd));
	$listAd = $em->getRepository('AMAPBundle:Acteur')->findBy(array('amap' => $session->get('amap'), 'typeActeur' => $typeAd));
        
        //var_dump($typeProd);
                
        return $this->render('AMAPBundle:Admin/Acteur:listerActeur.html.twig',array('page_courante' => 'AdminActeurs', 'onglet_courant' => 'listerActeurAction','listAd'=>$listAd,'listProd'=>$listProd));
    }
    
    public function creerActeurAction(Request $request){
        
        $em = $this->getDoctrine()->getManager();
        
        $session = $request->getSession();

        $form = $this->get('form.factory')->createNamedBuilder('formulaire_creation_acteur')
            ->add('email',EmailType::class)
            ->add('typeActeur',EntityType::class,array('class' => 'AMAPBundle:TypeActeur', 'choice_label' => 'libelle'))
            ->add('nom', TextType::class )
            ->add('prenom', TextType::class )
            ->add('dateNaissance', DateType::class, array('input'=>'datetime','years' => range(1900, date('Y'))))
			->add('numRue',TextType::class)
            ->add('typeVoie', TextType::class )
            ->add('nomVoie', TextType::class )
            ->add('ville', TextType::class )
            ->add('cp', TextType::class )
            ->add('amap',EntityType::class,array('class' => 'AMAPBundle:Amap', 
                'choice_label' => 'libelle',
                'query_builder' => $this->getDoctrine()->getRepository('AMAPBundle:Amap')->getAmap($session->get('amap'))))
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

                $adresse = new Adresse();

                $adresse->setNumRue($data['numRue']);
                $adresse->setTypeVoie($data['typeVoie']);
                $adresse->setNomVoie($data['nomVoie']);
                $adresse->setville($data['ville']);
                $adresse->setCp($data['cp']);

		$acteur->setAdresse($adresse);
                
                $acteur->setEmail($data['email']);
                $acteur->setEmailCanonical($data['email']);
		$acteur->setAmap($data['amap']);

		$em->persist($adresse);
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
        return $this->render('AMAPBundle:Admin/Acteur:creerActeur.html.twig',array('page_courante' => 'AdminActeurs', 'onglet_courant' => 'creerActeurAction',
            'form' => $form->createView(),
            'form2' => $form2->createView()));
    }
    /********************************
     ********* FIN ACTEUR **********
     *******************************/
    
    /********************************
     ******** DEBUT CONTRAT *********
     *******************************/
    
    public function creerContratAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $session = $request->getSession();        

        $form = $this->get('form.factory')->createNamedBuilder('formulaire_creer_contrat')
            ->add('producteur',EntityType::class,array('class' => 'AMAPBundle:Acteur',
                                                        'choice_label' => 'nom',
                                                        'query_builder' => $this->getDoctrine()
                    ->getRepository('AMAPBundle:Acteur')
                    ->getProducteurs(1,$session->get('amap'))))
            ->add('consommateur',EntityType::class,array('class' => 'AMAPBundle:Acteur',
                                                         'choice_label' => 'nom',
                                                         'query_builder' => $this->getDoctrine()
                    ->getRepository('AMAPBundle:Acteur')
                    ->getActeurs(2,$session->get('amap'))))
            ->add('amap',EntityType::class,array('class' => 'AMAPBundle:Amap', 
                'choice_label' => 'libelle',
                'query_builder' => $this->getDoctrine()
                    ->getRepository('AMAPBundle:Amap')
                    ->getAmap($session->get('amap'))))
            ->add('panier',EntityType::class,array('class' => 'AMAPBundle:Panier', 
                'choice_label' => 'libelle',
                'query_builder' => $this->getDoctrine()
                    ->getRepository('AMAPBundle:Panier')
                    ->getAmap($session->get('amap'))))
            ->add('ajouter', SubmitType::class, array('label' => 'Créer contrat'))
            ->getForm();

        if ($form->handleRequest($request)->isSubmitted()){
           if ($form->get('ajouter')->isClicked())
           {
                $data = $form->getData();

                $contrat = new Contrat();

                $contrat->setProducteur($data['producteur']);
                $contrat->setConsommateur($data['consommateur']);
				$contrat->setAmap($data['amap']);
                $contrat->setPanier($data['panier']);

                $em->persist($contrat);
                $em->flush();
           }
        }
        
        return $this->render('AMAPBundle:Admin/Contrat:creerContrat.html.twig',array('page_courante' => 'AdminContrats', 'onglet_courant' => 'creerContratAction',
            'form' => $form->createView()));
    }
    
    public function creerContratInscriptionAction(Request $request,$idActeur, $idPanier )
    {
        $em = $this->getDoctrine()->getManager();
        
        $session = $request->getSession();

        $form = $this->get('form.factory')->createNamedBuilder('formulaire_creer_contrat')
            ->add('producteur',EntityType::class,array('class' => 'AMAPBundle:Acteur',
                                                        'choice_label' => 'nom',
                                                        'query_builder' => $this->getDoctrine()
                    ->getRepository('AMAPBundle:Acteur')
                    ->getProducteurs(1,$session->get('amap'))))
            ->add('consommateur',EntityType::class,array('class' => 'AMAPBundle:Acteur',
                                                         'choice_label' => 'nom',
                                                         'query_builder' =>  $this->getDoctrine()
                    ->getRepository('AMAPBundle:Acteur')
                    ->getActeur($idActeur)))
            ->add('amap',EntityType::class,array('class' => 'AMAPBundle:Amap', 
                'choice_label' => 'libelle',
                'query_builder' => $this->getDoctrine()
                    ->getRepository('AMAPBundle:Amap')
                    ->getAmap($session->get('amap'))))
            ->add('panier',EntityType::class,array('class' => 'AMAPBundle:Panier',
                'choice_label' => 'libelle',
                'query_builder' => $this->getDoctrine()
                    ->getRepository('AMAPBundle:Panier')
                    ->getPanier($idPanier)))
            ->add('ajouter', SubmitType::class, array('label' => 'Créer contrat'))
            ->getForm();

        if ($form->handleRequest($request)->isSubmitted()){
           if ($form->get('ajouter')->isClicked())
           {
                $data = $form->getData();

                $contrat = new Contrat();

                $contrat->setProducteur($data['producteur']);
                $contrat->setConsommateur($data['consommateur']);
		$contrat->setAmap($data['amap']);
                $contrat->setPanier($data['panier']);

                $this->getDoctrine()->getRepository('AMAPBundle:Inscription')->deleteInscription($data['consommateur']->getId(),$data['panier']->getId(),$data['amap']->getId());
                
                $em->persist($contrat);
                $em->flush();
           }
        }
        
        return $this->render('AMAPBundle:Admin/Contrat:creerContrat.html.twig',array('page_courante' => 'AdminContrats', 'onglet_courant' => 'creerContratAction',
            'form' => $form->createView()));
    }
    
    public function listerContratsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $session = $request->getSession();
        
        $listcontrat = $em->getRepository('AMAPBundle:Contrat')->findBy(array('amap'=>$session->get('amap')));
        
         return $this->render('AMAPBundle:Admin/Contrat:listerContrats.html.twig',array('page_courante' => 'AdminContrats', 'onglet_courant' => 'listerContratsAction',
            'listcontrat' => $listcontrat));
    }
    
    /********************************
     ********* FIN CONTRAT **********
     *******************************/
    
    /********************************
     ********* DEBUT AMAP ***********
     *******************************/
    
    public function creerAmapAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->get('form.factory')->createNamedBuilder('formulaire_creation_amap')
            ->add('libelle',TextType::class)
			->add('numRue',TextType::class)
            ->add('typeVoie', TextType::class )
            ->add('nomVoie', TextType::class )
            ->add('ville', TextType::class )
            ->add('cp', TextType::class )
            ->add('ajouter', SubmitType::class, array('label' => 'Créer amap'))
            ->getForm();

        if ($form->handleRequest($request)->isSubmitted()){
           if ($form->get('ajouter')->isClicked())
           {
                $data = $form->getData();

                $amap = new Amap();

		$amap->setLibelle($data['libelle']);

                $adresse = new Adresse();

                $adresse->setNumRue($data['numRue']);
                $adresse->setTypeVoie($data['typeVoie']);
                $adresse->setNomVoie($data['nomVoie']);
                $adresse->setville($data['ville']);
                $adresse->setCp($data['cp']);
                
                $amap->setAdresse($adresse);

		$em->persist($adresse);
                $em->persist($amap);

                $em->flush();

                //return $this->redirect($this->generateUrl('amap_panier_ajouter'));
           }

        }
        
        return $this->render('AMAPBundle:Admin/Amap:creerAmap.html.twig',array('page_courante' => 'AdminAmap', 'onglet_courant' => 'creerAmapAction',
            'form' => $form->createView()));
    }
    
    public function listerAmapAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $listAmap = $em->getRepository('AMAPBundle:Amap')->findAll();
        
        return $this->render('AMAPBundle:Admin/Amap:listerAmap.html.twig',array('page_courante' => 'AdminAmap', 'onglet_courant' => 'listerAmapAction','listamap' => $listAmap));
    }
    
    /********************************
     ********* FIN AMAP *************
     *******************************/
    
    /********************************
     ********* DEBUT STOCK **********
     *******************************/
    
    public function listerStockAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $session = $request->getSession();
        
        $entrepot = $em->getRepository('AMAPBundle:Entrepot')->findOneBy(array('amap'=>$session->get('amap')));

        return $this->render('AMAPBundle:Admin/Stock:listerStock.html.twig',array('page_courante' => 'AdminStock', 'onglet_courant' => 'listerStockAction','entrepot' => $entrepot));
    }
    
    public function ajouterStockAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $session = $request->getSession();
        
        /* Création formulaire pour ajouter une quantité d'un produit au stock */
        $form = $this->get('form.factory')->createNamedBuilder('formulaire_stock')
            ->add('entrepot', EntityType::class, array('class' => 'AMAPBundle:Entrepot',
                'choice_label' => 'libelle',
                'query_builder' => $this->getDoctrine()->getRepository('AMAPBundle:Panier')->getAmap($session->get('amap'))))
            ->add('produit', EntityType::class,array('class' => 'AMAPBundle:Produit', 'choice_label' => 'libelle'))
            ->add('quantite', IntegerType::class)
            ->add('ajouter', SubmitType::class, array('label' => 'Ajouter au stock'))
            ->getForm();

        if ($form->handleRequest($request)->isSubmitted()){

           /* Gestion du formulaire pour ajouter une quantité d'un produit au stock */
           if ($form->get('ajouter')->isClicked())
           {
                $data = $form->getData();

                $produit = $data['produit'];
                $quantite = $data['quantite'];
				$entrepot = $data['entrepot'];

                if($em->getRepository('AMAPBundle:Stock')->findBy(array('produit' =>$produit, 'entrepot' => $entrepot)))
                {
                    $stock = $em->getRepository('AMAPBundle:Stock')->findBy(array('produit' =>$produit, 'entrepot' => $entrepot));

                    $stock[0]->setQuantite($stock[0]->getQuantite()+$quantite);
                    $em->persist($stock[0]);
                }
                else
                {
                    $stock = new Stock();

                    $stock->setProduit($produit);
                    $stock->setQuantite($quantite);
					$stock->setEntrepot($entrepot);

					$entrepot->addStock($stock);

                    $em->persist($stock);
					$em->persist($entrepot);
                }

                $em->flush();
            }
        }

        return $this->render('AMAPBundle:Admin/Stock:ajouterStock.html.twig',array('page_courante' => 'AdminStock', 'onglet_courant' => 'ajouterStockAction','form' => $form->createView()));
    }
    
    /********************************
     ********** FIN STOCK ***********
     *******************************/
    
    /********************************
     ******** DEBUT ENTREPOT ********
     *******************************/
    
    public function listerEntrepotAction()
    {
        $em = $this->getDoctrine()->getManager();

	$entrepots = $em->getRepository('AMAPBundle:Entrepot')->findAll();

        return $this->render('AMAPBundle:Admin/Entrepot:listerEntrepot.html.twig',array('page_courante' => 'AdminEntrepot', 'onglet_courant' => 'listerEntrepotAction','listEntrepot' => $entrepots));
    }
    
    public function creerEntrepotAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->get('form.factory')->createNamedBuilder('formulaire_entrepot')
            ->add('libelle', TextType::class)
            ->add('numRue',TextType::class)
            ->add('typeVoie', TextType::class )
            ->add('nomVoie', TextType::class )
            ->add('ville', TextType::class )
            ->add('cp', TextType::class )
            ->add('amap',  EntityType::class, array('class' => 'AMAPBundle:Amap', 'choice_label' => 'libelle'))
            ->add('creer', SubmitType::class, array('label' => 'Créer un entrepot'))        
            ->getForm();

        if ($form->handleRequest($request)->isSubmitted()){

           if ($form->get('creer')->isClicked())
           {
                $data = $form->getData();

                $entrepot = new Entrepot();

                $entrepot->setLibelle($data['libelle']);
                $entrepot->setAmap($data['amap']);

		$adresse = new Adresse();

                $adresse->setNumRue($data['numRue']);
                $adresse->setTypeVoie($data['typeVoie']);
                $adresse->setNomVoie($data['nomVoie']);
                $adresse->setville($data['ville']);
                $adresse->setCp($data['cp']);

		$entrepot->setAdresse($adresse);

                $em->persist($adresse);
                $em->persist($entrepot);

                $em->flush();
            }
        }

        return $this->render('AMAPBundle:Admin/Entrepot:creerEntrepot.html.twig',array('page_courante' => 'AdminEntrepot', 'onglet_courant' => 'creerEntrepotAction','form' => $form->createView()));
    }
    
    /********************************
     ********* FIN ENTREPOT *********
     *******************************/
    
    /********************************
     ******** DEBUT LIVRAISON *******
     *******************************/
    
    public function listerLivraisonAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $session = $request->getSession();

        $listContrat = $em->getRepository('AMAPBundle:Contrat')->findBy(array('amap'=>$session->get('amap')));

        return $this->render('AMAPBundle:Admin/Livraison:listerLivraison.html.twig',array('page_courante' => 'AdminLivraison', 'onglet_courant' => 'listerLivraisonAction','listContrat' => $listContrat));
    }
    
    public function ajouterLivraisonAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $session = $request->getSession();

        $form = $this->get('form.factory')->createNamedBuilder('formulaire_livraison')
            ->add('dateTime', DateTimeType::class)
            ->add('contrat',EntityType::class, array('class' => 'AMAPBundle:Contrat',
                'choice_label' => 'id',
                'query_builder' => $this->getDoctrine()
                    ->getRepository('AMAPBundle:Contrat')
                    ->getContrats(1,$session->get('amap'))))
            ->add('ajouter', SubmitType::class, array('label' => 'Ajouter livraison'))
            ->getForm();

        if ($form->handleRequest($request)->isSubmitted())
        {

           /* Gestion du formulaire pour ajouter une quantité d'un produit au stock */
           if ($form->get('ajouter')->isClicked())
           {
                $data = $form->getData();

                $dateLivraison = $data['dateTime'];
                $contrat = $data['contrat'];

                $livraison = new Livraison();

                $livraison->setDateLivraison($dateLivraison);
                $livraison->setEstLivree(false);

                $contrat->addLivraison($livraison);

                $em->persist($livraison);
		$em->persist($contrat);

                $em->flush();
            }
            
        }

        return $this->render('AMAPBundle:Admin/Livraison:ajouterLivraison.html.twig',array('page_courante' => 'AdminLivraison', 'onglet_courant' => 'ajouterLivraisonAction','form' => $form->createView()));
    }
    
    public function preparerLivraisonAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $session = $request->getSession();
        
	$form = $this->get('form.factory')->createNamedBuilder('formulaire_effectuer_livraison')
           ->add('panier', EntityType::class, array('class' => 'AMAPBundle:Panier',
                'choice_label' => 'libelle',
                'query_builder' => $this->getDoctrine()->getRepository('AMAPBundle:Panier')->getAmap($session->get('amap'))))
            ->add('livrer', SubmitType::class, array('label' => 'Préparer la livraison'))
            ->getForm();


        if ($form->handleRequest($request)->isSubmitted())
        {
            if ($form->get('livrer')->isClicked())
            {
		$data = $form->getData();

		return $this->redirect($this->generateUrl('amap_effectuer_livraison', array('id'=>$data['panier']->getId())));
            }
        }

        return $this->render('AMAPBundle:Admin/Livraison:preparerLivraison.html.twig',array('page_courante' => 'AdminLivraison', 'onglet_courant' => 'ajouterLivraisonAction','form' => $form->createView()));
    }
    
    public function effectuerLivraisonAction(Request $request,$id)
    {
	$em = $this->getDoctrine()->getManager();
		
	$listContrat = $em->getRepository('AMAPBundle:Contrat')->findBy(array('panier' => $id));

	$form = $this->get('form.factory')->createNamedBuilder('formulaire_effectuer_livraison')
                     ->add('livrer', SubmitType::class, array('label' => 'Effectuer la livraison'))
                     ->getForm();

        if ($form->handleRequest($request)->isSubmitted()){
            if ($form->get('livrer')->isClicked()){
                foreach ($listContrat as $contrat) {
                    foreach ($contrat->getLivraisons() as $livraison) {
                        
                        
                        $panier = $contrat->getPanier();

                        $panierproduit = $panier->getPanierproduit();
                            
                        $entrepot = $em->getRepository('AMAPBundle:Entrepot')->findOneBy(array('amap'=>$request->getSession()->get('amap')));
                        
                        foreach ($panierproduit as $produit) {

                            $stock = $em->getRepository('AMAPBundle:Stock')->findBy(array('produit' => $produit->getProduit(), 'entrepot' => $entrepot));

                            $stock[0]->setQuantite($stock[0]->getQuantite() - $produit->getQuantite());

                            $em->persist($stock[0]);

                        }                        
                        
                        $livraison->setEstLivree(true);
                                
                        $em->persist($livraison);                                
                    }
                }
            }
                    
            $em->flush();
             
            return $this->redirect($this->generateUrl('amap_effectuer_livraison', array('id'=>$id)));
        }
                
                
	return $this->render('AMAPBundle:Admin/Livraison:effectuerLivraison.html.twig',array('page_courante' => 'AdminLivraison', 'onglet_courant' => 'effectuerLivraisonAction','form' => $form->createView(),
                                                                                              'listContrat'=>$listContrat));
               
    }
    
    public function effectuerOneAction(Request $request,$idpanier,$id,$date)
    {
            $em = $this->getDoctrine()->getManager();
            
            $contrat = $em->getRepository('AMAPBundle:Contrat')->findBy(array('id' => $id));
            $listContrat = $em->getRepository('AMAPBundle:Contrat')->findBy(array('panier' => $idpanier));
            
            foreach ($contrat[0]->getLivraisons() as $livraison) {
               
                if($livraison->getDateLivraison()->format('Y-m-d H:i:s') == $date)
                {
                    $livraison->setEstLivree(true);
                    
                    $panier = $contrat[0]->getPanier();

                        $panierproduit = $panier->getPanierproduit();
                            
                        $entrepot = $em->getRepository('AMAPBundle:Entrepot')->findOneBy(array('amap'=>$request->getSession()->get('amap')));
                        
                        foreach ($panierproduit as $produit) {

                            $stock = $em->getRepository('AMAPBundle:Stock')->findBy(array('produit' => $produit->getProduit(), 'entrepot' => $entrepot));

                            $stock[0]->setQuantite($stock[0]->getQuantite() - $produit->getQuantite());

                            $em->persist($stock[0]);

                        } 
                    
                    $em->persist($livraison);
                }
            }
            
            $em->flush();
            
            return $this->redirect($this->generateUrl('amap_effectuer_livraison', array('id'=>$idpanier)));
            
    }
   
    /********************************
     ********* FIN LIVRAISON ********
     *******************************/

}