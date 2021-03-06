<?php 
namespace AppBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Security\Core\User\UserInterface;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\Table(name="user")
 */

class User implements UserInterface{

	/**
    * @ORM\Column(type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    protected $id;
  
	/**
     * @ORM\Column(type="string", length=100)
     */
	protected $nom;
	
	/**
     * @ORM\Column(type="string", length=100)
     */
	protected $prenom;
	/**
     * @ORM\Column(type="integer", nullable=true)
     */
	protected $statut = 0;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */

    //protected $roles = ['ROLE_USER'];
	/**
    * @ORM\Column(type="string", length=200)
    */
	protected $email;
        
    /**
    * @ORM\Column(type="string", length=200)
    */
    protected $mdp;
	
	/**
     * @ORM\Column(type="string", length=5)
     */
	protected $annee;
	
	
	/**
     * @ORM\Column(type="string", length=100)
     */
	protected $ecole;
	
    /**
    * @ORM\OneToMany(targetEntity="Staff", mappedBy="user")
    */
    protected $staffs;

	/**
     * 
     */
	protected $date_crea;


    /**
    * @ORM\OneToMany(targetEntity="Participations", mappedBy="user_id") 
    */
    protected $participations;

    
    public function __construct()
    {
        //$this->participations = new ArrayCollection();
    }


	public static function loadValidatorMetadata(ClassMetadata $metadata){
        $metadata->addPropertyConstraint('nom', new NotBlank());
        $metadata->addPropertyConstraint('prenom', new NotBlank());
    }

	public function getId(){return $this->id;}
	public function getStatut(){return $this->statut;}
	public function getNom(){return $this->nom;}
	public function getPrenom(){return $this->prenom;}
	public function getAnnee(){return $this->annee;}
    public function getEmail(){return $this->email;}
    public function getMdp(){return $this->mdp;}
	public function getEcole(){return $this->ecole;}
	public function getDate_crea(){return $this->date_crea;}

    public function getRoles(){
        if($this->getStatut() == 1)
            return ['ROLE_USER', 'ROLE_ADMIN'];

        return ['ROLE_USER'];
    }

	public function setId($id){$this->id = $id;}
	public function setStatut($statut){$this->statut = $statut;}
	public function setNom($nom){$this->nom = strtoupper(trim($nom));}
	public function setPrenom($prenom){$this->prenom = strtoupper(trim($prenom));}
	public function setAnnee($annee){$this->annee = $annee;}
	public function setEmail($email){$this->email = $email;}
	public function setMdp($mdp){$this->mdp = $mdp;}
	public function setEcole($ecole){$this->ecole = strtoupper(trim($ecole));}
	public function setDate_crea($date_crea){$this->date_crea = $date_crea;}

    //Fonction essentielle pour le formBuilder Participation
    public function __toString(){
        return $this->nom." ".$this->prenom;
    }

    public function isEqualTo(UserInterface $user){
        return $this->id === $user->getId();
    }

    public function eraseCredentials() {
        $this->setMdp(NULL);
    }

    public function getPassword() {
        return $this->mdp;
    }

    public function getSalt() {
        return null;
    }

    public function getUsername() {
        return $this->email;
    }

}