<?php
namespace Skel\Model;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Type;

/**
 * @ORM\Entity
 * @ORM\Table(name="User")
 */
class User extends Entity
{
    /**
     * @ORM\Column(type="string", length=150)
     * @Type("string")
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=150, unique=true)
     * @Type("string")
     * @var string
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=100)
     * @Type("string")
     * @var string
     */
    private $password;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Type("boolean")
     * @var boolean
     */
    private $admin;

    public function getName()
    {
        return $this->name;
    }
    
    public function setName($name)
    {
        return $this->name = filter_var($name, FILTER_SANITIZE_STRING);
    }
        
    public function getEmail()
    {
        return $this->email;
    }
    
    public function setEmail($email)
    {
    	if (FALSE === filter_var($email, FILTER_VALIDATE_EMAIL)) {
    		throw new \InvalidArgumentException('INVALID EMAIL');
    	}
        return $this->email = $email;
    }
    
    public function getPassword()
    {
        return $this->password;
    }
    
    public function setPassword($password)
    {

        return $this->password = $password;
    }

    public function getAdmin()
    {
        return $this->admin;
    }
    
    public function setAdmin($admin)
    {
        return $this->admin = $admin;
    }
}
