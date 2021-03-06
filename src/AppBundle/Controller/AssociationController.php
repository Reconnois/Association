<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Association;
use AppBundle\Entity\Participations;
use AppBundle\Entity\User;
use AppBundle\Entity\Staff;
use AppBundle\Repository\AssociationRepository;
use AppBundle\Form\AssociationForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;



class AssociationController extends Controller
{
    /**
     * @Route("/associations", name="associations")
     */
    public function associationsAction(Request $request)
    {
        
        $em = $this->getDoctrine()->getManager();
        $association = $em->getRepository('AppBundle:Association')->findAll();
      
        return $this->render('open_association\listeAssociation.html.twig', [
            'Associations'=>$association,
        ]);
    }
    /**
     * @Route("/addassociation", name="add_associations")
     */
    public function addAction(Request $request)
    {
        if($this->getUser() && $this->getUser()->getStatut()==1){
            $association = new Association();
            $form = $this->createForm(AssociationForm::class, $association);
            $form->handleRequest($request);
            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->persist($association);
                $em->flush();
                return $this->redirectToRoute('associations');
            }
            return $this->render('open_association\addAssociation.html.twig', [
                'form'=>$form->createView(),
            ]);
        }else{
            return $this->render('default\NotAllowed.html.twig', []);
        }
    }
    /**
     * @Route("/association/edit/{id}", name="edit_association")
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $association = $em->getRepository('AppBundle:Association')->find($id);
        $form = $this->createForm(AssociationForm::class, $association);
        $form->handleRequest($request);
        if($form->isValid()){
           $em->persist($association);
           $em->flush();
           $this->addFlash('success', "Les informations de l'association ont été correctement modifiées.");
           return $this->redirectToRoute('associations', []);
        }
        return $this->render('open_association\addAssociation.html.twig', [
            'form'=>$form->createView(),
        ]);
    }

     /**
     * @Route("/show/{id}", name="show_association")
     */
    public function showAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $association = $em->getRepository('AppBundle:Association')->find($id);
        $reponsables = $em->getRepository('AppBundle:Staff')->findBy(array('association'=>$id));
        $participants = $em->getRepository('AppBundle:Participations')->findBy(array('association_id'=>$id));
        $users= array();
        foreach ($participants as $participant)
            $users[] = $em->getRepository('AppBundle:User')->find($participant->getUser_id());

        $respo= array();
        foreach ($reponsables as $reponsable)
            $respo[] = $em->getRepository('AppBundle:User')->find($reponsable->getUser());        
       
        return $this->render('open_association\showAssociation.html.twig', [
            'association'=>$association,
            'users'=>$users,
            'responsables'=>$respo,
        ]);
    }
    

    /**
     * @Route("/association/delete/{id}", name="delete_association")
     */
    public function deleteAction(Request $request, $id)
    {
        if($this->getUser() && $this->getUser()->getStatut()==1){
            $em = $this->getDoctrine()->getManager();
            
            //suppression des participations
            $participations = $em->getRepository(Participations::class)
                                  ->getParticipationsByAssoc($id);
            if($participations){
              foreach ($participations as $participation)
                $em->remove($participation);
            }

            //suppression des responsabilites
            $responsibilities = $em->getRepository(Staff::class)
                          ->getResponsibilitiesByAssoc($id);
            if($responsibilities){
              foreach ($responsibilities as $responsibility)
                $em->remove($responsibility);
            }

            $association = $em->getRepository(Association::class)->getById(['id' => $id]);
            $em->remove($association);
            $em->flush();
            return $this->redirectToRoute('associations', []);
        }
        else
            return $this->render('default\NotAllowed.html.twig', []);
    }   
    

}
