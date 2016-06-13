<?php

namespace AMAPBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use \AMAPBundle\Entity\Panier;
use \AMAPBundle\Entity\PanierProduit;
use \AMAPBundle\Entity\Produit;
use \AMAPBundle\Entity\Saison;
use \AMAPBundle\Entity\Acteur;
use \AMAPBundle\Entity\Adresse;
use \AMAPBundle\Entity\TypeActeur;
use \Symfony\Component\Form\Extension\Core\Type\IntegerType;
use \Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use \Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AMAPBundle:Default:index.html.twig',array('page_courante' => 'default'));
    }

    public function selectAmapAction(Request $request)
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
			return $this->redirectToRoute('amap_homepage');
		}

		 return $this->render('AMAPBundle:Default:selectAmap.html.twig',array('form' => $form->createView(),
                                                                                        'page_courante' => 'default'));
    }
}