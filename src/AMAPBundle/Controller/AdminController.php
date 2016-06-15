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


class AdminController extends Controller
{
    public function indexAction(Request $request)
    {

        return $this->render('admin.html.twig',array());
    }

    /*****************************
     ******* DEBUT PANIER  *******
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
    
    public function retraitPanierAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $form = $this->get('form.factory')->createNamedBuilder('formulaire_retirer_panier')
            ->add('panier', EntityType::class, array('class' => 'AMAPBundle:Panier', 'choice_label' => 'libelle') )
	    ->add('amap', EntityType::class, array('class' => 'AMAPBundle:Amap', 'choice_label' => 'libelle'))
            ->add('ajouter', SubmitType::class, array('label' => 'Retirer un Panier'))
            ->getForm();
        
        return $this->render('AMAPBundle:Admin/Panier:retrait.html.twig',array('form'=>$form->createView()));
    }
    
    public function consulterAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $paniers = $em->getRepository('AMAPBundle:Panier')->findAll();
        
        return $this->render('AMAPBundle:Admin/Panier:consulterPaniers.html.twig',array('paniers'=>$paniers));
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
                
        return $this->render('AMAPBundle:Admin/Acteur:listerActeur.html.twig',array('listAd'=>$listAd,'listProd'=>$listProd));
    }
    
    public function creerActeurAction(Request $request){
        
        $em = $this->getDoctrine()->getManager();

        $form = $this->get('form.factory')->createNamedBuilder('formulaire_creation_acteur')
            ->add('typeActeur',EntityType::class,array('class' => 'AMAPBundle:TypeActeur', 'choice_label' => 'libelle'))
            ->add('nom', TextType::class )
            ->add('prenom', TextType::class )
            ->add('dateNaissance', DateType::class, array('input'=>'datetime','years' => range(1900, date('Y'))))
			->add('numRue',TextType::class)
            ->add('typeVoie', TextType::class )
            ->add('nomVoie', TextType::class )
            ->add('ville', TextType::class )
            ->add('cp', TextType::class )
			->add('amap',EntityType::class,array('class' => 'AMAPBundle:Amap', 'choice_label' => 'libelle'))
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
        return $this->render('AMAPBundle:Admin/Acteur:creerActeur.html.twig',array(
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

        $form = $this->get('form.factory')->createNamedBuilder('formulaire_creer_contrat')
            ->add('producteur',EntityType::class,array('class' => 'AMAPBundle:Acteur',
                                                        'choice_label' => 'nom',
                                                        'query_builder' => function(\Doctrine\ORM\EntityRepository $er){
                                                            return $er->createQueryBuilder('s')
                                                            ->where('s.typeActeur = ?1')
                                                            ->setParameter(1,'1');
                                                        }))
            ->add('consommateur',EntityType::class,array('class' => 'AMAPBundle:Acteur',
                                                         'choice_label' => 'nom',
                                                         'query_builder' => function(\Doctrine\ORM\EntityRepository $er){
                                                            return $er->createQueryBuilder('s')
                                                            ->where('s.typeActeur = ?1')
                                                            ->setParameter(1,'2');
                                                        }))
			->add('amap',EntityType::class,array('class' => 'AMAPBundle:Amap', 'choice_label' => 'libelle'))
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
				$contrat->setAmap($data['amap']);
                $contrat->setPanier($data['panier']);

                $em->persist($contrat);
                $em->flush();
           }
        }
        
        return $this->render('AMAPBundle:Admin/Contrat:creerContrat.html.twig',array(
            'form' => $form->createView()));
    }
    
    public function listerContratsAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $listcontrat = $em->getRepository('AMAPBundle:Contrat')->findAll();
        
         return $this->render('AMAPBundle:Admin/Contrat:listerContrats.html.twig',array(
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
        
        return $this->render('AMAPBundle:Admin/Amap:creerAmap.html.twig',array(
            'form' => $form->createView()));
    }
    
    public function listerAmapAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $listAmap = $em->getRepository('AMAPBundle:Amap')->findAll();
        
        return $this->render('AMAPBundle:Admin/Amap:listerAmap.html.twig',array('listamap' => $listAmap));
    }
    
    /********************************
     ********* FIN AMAP *************
     *******************************/
    
    /********************************
     ********* DEBUT STOCK **********
     *******************************/
    
    public function listerStockAction()
    {
        $em = $this->getDoctrine()->getManager();

        $stockFinal = $em->getRepository('AMAPBundle:Stock')->findAll();

        return $this->render('AMAPBundle:Admin/Stock:listerStock.html.twig',array('stock' => $stockFinal));
    }
    
    public function ajouterStockAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();


        /* Création formulaire pour ajouter une quantité d'un produit au stock */
        $form = $this->get('form.factory')->createNamedBuilder('formulaire_stock')
			->add('entrepot', EntityType::class,array('class' => 'AMAPBundle:Entrepot', 'choice_label' => 'libelle'))
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

        return $this->render('AMAPBundle:Admin/Stock:ajouterStock.html.twig',array('form' => $form->createView()));
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

        return $this->render('AMAPBundle:Admin/Entrepot:listerEntrepot.html.twig',array('listEntrepot' => $entrepots));
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
            ->add('creer', SubmitType::class, array('label' => 'Créer un entrepot'))
            ->getForm();

        if ($form->handleRequest($request)->isSubmitted()){

           if ($form->get('creer')->isClicked())
           {
                $data = $form->getData();

                $entrepot = new Entrepot();

				$entrepot->setLibelle($data['libelle']);

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

        return $this->render('AMAPBundle:Admin/Entrepot:creerEntrepot.html.twig',array('form' => $form->createView()));
    }
    
    /********************************
     ********* FIN ENTREPOT *********
     *******************************/
    
    /********************************
     ******** DEBUT LIVRAISON *******
     *******************************/
    
    public function listerLivraisonAction()
    {
        $em = $this->getDoctrine()->getManager();

        $listContrat = $em->getRepository('AMAPBundle:Contrat')->findAll();

        return $this->render('AMAPBundle:Admin/Livraison:listerLivraison.html.twig',array('listContrat' => $listContrat));
    }
    
    public function ajouterLivraisonAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();


        $form = $this->get('form.factory')->createNamedBuilder('formulaire_livraison')
            ->add('dateTime', DateTimeType::class)
            ->add('contrat',EntityType::class, array('class' => 'AMAPBundle:Contrat', 'choice_label' => 'id'))
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

        return $this->render('AMAPBundle:Admin/Livraison:ajouterLivraison.html.twig',array('form' => $form->createView()));
    }
    
    public function preparerLivraisonAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

	$form = $this->get('form.factory')->createNamedBuilder('formulaire_effectuer_livraison')
            ->add('panier', EntityType::class, array('class' => 'AMAPBundle:Panier', 'choice_label' => 'libelle'))
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

        return $this->render('AMAPBundle:Admin/Livraison:preparerLivraison.html.twig',array('form' => $form->createView()));
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
                        
                        $livraison->setEstLivree(true);
                                
                        $em->persist($livraison);                                
                    }
                }
            }
                    
            $em->flush();
             
            return $this->redirect($this->generateUrl('amap_effectuer_livraison', array('id'=>$id)));
        }
                
                
	return $this->render('AMAPBundle:Admin/Livraison:effectuerLivraison.html.twig',array('form' => $form->createView(),
                                                                                              'listContrat'=>$listContrat));
               
    }
    
    public function effectuerOneAction($idpanier,$id,$date)
    {
            $em = $this->getDoctrine()->getManager();
            
            $contrat = $em->getRepository('AMAPBundle:Contrat')->findBy(array('id' => $id));
            $listContrat = $em->getRepository('AMAPBundle:Contrat')->findBy(array('panier' => $idpanier));
            
            foreach ($contrat[0]->getLivraisons() as $livraison) {
               
                if($livraison->getDateLivraison()->format('Y-m-d H:i:s') == $date)
                {
                    $livraison->setEstLivree(true);
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