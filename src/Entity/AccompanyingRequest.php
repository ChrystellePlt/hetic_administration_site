<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="app_accompanying_requests")
 * @ORM\Entity(repositoryClass="App\Repository\AccompanyingRequestRepository")
 * @UniqueEntity(fields="email", message="Cet e-mail existe déjà")
 */
class AccompanyingRequest
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

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
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=20)
     */
    private $wantedPromotion;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=20)
     */
    private $actualSchoolLevel;

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
     * @ORM\Column(type="string")
     */
    private $wantedSpeciality;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
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
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getWantedPromotion()
    {
        return $this->wantedPromotion;
    }

    /**
     * @param mixed $wantedPromotion
     */
    public function setWantedPromotion($wantedPromotion): void
    {
        $this->wantedPromotion = $wantedPromotion;
    }

    /**
     * @return mixed
     */
    public function getActualSchoolLevel()
    {
        return $this->actualSchoolLevel;
    }

    /**
     * @param mixed $actualSchoolLevel
     */
    public function setActualSchoolLevel($actualSchoolLevel): void
    {
        $this->actualSchoolLevel = $actualSchoolLevel;
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
    public function getWantedSpeciality()
    {
        return $this->wantedSpeciality;
    }

    /**
     * @param mixed $wantedSpeciality
     */
    public function setWantedSpeciality($wantedSpeciality): void
    {
        $this->wantedSpeciality = $wantedSpeciality;
    }
}
