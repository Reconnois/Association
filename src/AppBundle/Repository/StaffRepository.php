<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Staff
 *
 * @author Guillaume QUIRIN
 */
namespace AppBundle\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use AppBundle\Repository\AssociationRepository;
use AppBundle\Entity\Association;
use AppBundle\Entity\Staff;


class StaffRepository extends EntityRepository
{
    public function getAssociationsByStaff($query = []){
        $staffs = $this->findBy(["user" => $query['user']]);
        if($staffs != null)
            return $staffs; 
        else
            return false;
    }
    
    public function getResponsibilitiesByAssoc($assoc_id){
        $responsibilities = $this->findBy(['association' => $assoc_id]);
        return $responsibilities;
    }

    public function isStaffExists(Staff $staff){
        $exists = $this->findBy(['association' => $staff->getAssociation(), 'user' => $staff->getUser()]);
        dump($exists);
        return $exists;
    }

    public static function delete($id){
        $participation = $this->findOneBy(["id" => $id]);
        $em->remove($participation);
        $em->flush();
    }

}