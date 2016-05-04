<?php

namespace AMAPBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use \AMAPBundle\Entity\Produit;
use \AMAPBundle\Entity\Contrat;
use \AMAPBundle\Entity\Stock;
use \AMAPBundle\Entity\Famille;
use \AMAPBundle\Entity\Saison;
use \AMAPBundle\Entity\Entrepot;
use \AMAPBundle\Entity\Livraison;
use \Symfony\Bridge\Doctrine\Form\Type\EntityType;
use \Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;



class LivraisonController extends Controller
{
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();


        $form = $this->get('form.factory')->createNamedBuilder('formulaire_livraison')
            ->add('dateTime', DateTimeType::class)
            ->add('contrat',EntityType::class, array('class' => 'AMAPBundle:Contrat', 'choice_label' => 'id'))
            ->add('ajouter', SubmitType::class, array('label' => 'Ajouter livraison'))
            ->getForm();

		$form2 = $this->get('form.factory')->createNamedBuilder('formulaire_effectuer_livraison')
            ->add('panier', EntityType::class, array('class' => 'AMAPBundle:Panier', 'choice_label' => 'libelle'))
            ->add('livrer', SubmitType::class, array('label' => 'Préparer la livraison'))
            ->getForm();


        if ($form->handleRequest($request)->isSubmitted() ||
				$form2->handleRequest($request)->isSubmitted()){

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
            if ($form2->get('livrer')->isClicked())
            {
		$data = $form2->getData();

		return $this->redirect($this->generateUrl('amap_effectue_livraison', array('id'=>$data['panier']->getId())));
            }
        }

		$listContrat = $em->getRepository('AMAPBundle:Contrat')->findAll();


        return $this->render('AMAPBundle:Livraison:index.html.twig',array('form' => $form->createView(),
                                                                            'form2' => $form2->createView(),
                                                                            'page_courante' => 'livraison',
                                                                            'listContrat' => $listContrat));
    }

	public function effectuerAction(Request $request,$id)
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
                    return $this->redirect($this->generateUrl('amap_effectue_livraison', array('id'=>$id)));
                }
                
                
		return $this->render('AMAPBundle:Livraison:effectuer.html.twig',array('form' => $form->createView(),
                                                                                    'page_courante' => 'livraison',
                                                                                    'listContrat' => $listContrat));
	}
        
        public function effectuerOneAction($idpanier,$id,$date){
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
            
            return $this->redirect($this->generateUrl('amap_effectue_livraison', array('id'=>$idpanier)));
            
        }

}
