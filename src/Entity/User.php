<?php

namespace App\Entity;

use App\Constraints as AppAssert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="app_users")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields="email", message="Cet e-mail existe déjà")
 * @UniqueEntity(fields="username", message="Ce nom d'utilisateur existe déjà")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     * @Assert\Length(
     *     min = 3,
     *     max = 24,
     *     minMessage = "Votre nom d'utilisateur doit faire plus de {{ limit }} caractères.",
     *     maxMessage = "Votre nom d'utilisateur doit faire moins de {{ limit }} caractères.",
     * )
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(name="first_name", type="string", length=70)
     * @Assert\Length(max=100)
     */
    private $firstName;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(name="last_name", type="string", length=70)
     * @Assert\Length(max=100)
     */
    private $lastName;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=20)
     */
    private $promotion;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $twitter;

    /**
     * @ORM\Column(type="string", length=140)
     */
    private $facebook;

    /**
     * @ORM\Column(name="phone_number", type="string", length=15)
     * @Assert\Regex(
     *      pattern  = "/^\+33[1-9]([-. ]?[0-9]{2}){4}$/",
     *      message = "Numéro de téléphone invalide"
     * )
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="array")
     */
    private $skills;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @ORM\Column(name="confirmation_token", type="string", length=64)
     */
    private $confirmationToken;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     * @AppAssert\EmailDomain(domains = {"hetic.net"})
     */
    private $email;

    /**
     * @ORM\Column(name="personnal_email", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $personnalEmail;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(name="role", type="array")
     */
    private $roles;

    public function __construct()
    {
        $this->isActive = true;
    }

    /**
     * @Assert\IsTrue(message="Le mot de passe ne peut être égal à votre nom d'utilisateur")
     */
    public function isPasswordLegal()
    {
        return $this->getPlainPassword() !== $this->getUsername();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getSalt()
    {
        return null;
    }

    public function isActive()
    {
        return $this->isActive;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getPersonnalEmail()
    {
        return $this->personnalEmail;
    }

    public function setPersonnalEmail($personnalEmail)
    {
        $this->personnalEmail = $personnalEmail;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function getRealRole()
    {
        return $this->roles[0];
    }

    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    public function eraseCredentials()
    {
    }

    /**
     * @return mixed
     */
    public function getConfirmationToken()
    {
        return $this->confirmationToken;
    }

    /**
     * @param mixed $confirmationToken
     */
    public function setConfirmationToken($confirmationToken): void
    {
        $this->confirmationToken = $confirmationToken;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getPromotion()
    {
        return $this->promotion;
    }

    /**
     * @param mixed $promotion
     */
    public function setPromotion($promotion): void
    {
        $this->promotion = $promotion;
    }

    /**
     * @return mixed
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * @param mixed $twitter
     */
    public function setTwitter($twitter): void
    {
        $this->twitter = $twitter;
    }

    /**
     * @return mixed
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * @param mixed $facebook
     */
    public function setFacebook($facebook): void
    {
        $this->facebook = $facebook;
    }

    /**
     * @return mixed
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @param mixed $phoneNumber
     */
    public function setPhoneNumber($phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @return mixed
     */
    public function getSkills()
    {
        return $this->skills;
    }

    /**
     * @param mixed $skills
     */
    public function setSkills($skills): void
    {
        $this->skills = $skills;
    }

    /**
     * @return mixed
     */
    public function getisActive()
    {
        return $this->isActive;
    }

    /**
     * @param mixed $isActive
     */
    public function setIsActive($isActive): void
    {
        $this->isActive = $isActive;
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->password,
            $this->roles,
        ]);
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->username,
            $this->password,
            $this->roles) = unserialize($serialized);
    }
}
