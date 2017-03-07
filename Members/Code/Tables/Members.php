<?php

namespace Social\Members\Code\Tables;

use Doctrine\ORM\Mapping as ORM;

/**
 * Members
 *
 * @ORM\Table(name="social_members", indexes={@ORM\Index(name="user_id_index", columns={"user_id"}), @ORM\Index(name="country_id_index", columns={"country_id"}), @ORM\Index(name="location_id_index", columns={"location_id"}), @ORM\Index(name="inviter_id_index", columns={"inviter_id"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Members extends \Kazist\Table\BaseTable {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar", type="string", length=255, nullable=true)
     */
    protected $avatar;

    /**
     * @var integer
     *
     * @ORM\Column(name="cover", type="integer", length=11, nullable=true)
     */
    protected $cover;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dob", type="date", nullable=true)
     */
    protected $dob;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=255, nullable=true)
     */
    protected $gender;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", length=11, nullable=false)
     */
    protected $user_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="country_id", type="integer", length=11, nullable=true)
     */
    protected $country_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="location_id", type="integer", length=11, nullable=true)
     */
    protected $location_id;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     */
    protected $city;

    /**
     * @var integer
     *
     * @ORM\Column(name="inviter_id", type="integer", length=11, nullable=false)
     */
    protected $inviter_id;

    /**
     * @var string
     *
     * @ORM\Column(name="id_passport", type="string", length=255, nullable=true)
     */
    protected $id_passport;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
     */
    protected $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="text", nullable=true)
     */
    protected $address;

    /**
     * @var integer
     *
     * @ORM\Column(name="points", type="integer", length=11, nullable=true)
     */
    protected $points;

    /**
     * @var integer
     *
     * @ORM\Column(name="created_by", type="integer", length=11, nullable=false)
     */
    protected $created_by;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_created", type="datetime", nullable=false)
     */
    protected $date_created;

    /**
     * @var integer
     *
     * @ORM\Column(name="modified_by", type="integer", length=11, nullable=false)
     */
    protected $modified_by;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_modified", type="datetime", nullable=false)
     */
    protected $date_modified;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     * @return Members
     */
    public function setAvatar($avatar) {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string 
     */
    public function getAvatar() {
        return $this->avatar;
    }

    /**
     * Set cover
     *
     * @param integer $cover
     * @return Members
     */
    public function setCover($cover) {
        $this->cover = $cover;

        return $this;
    }

    /**
     * Get cover
     *
     * @return integer 
     */
    public function getCover() {
        return $this->cover;
    }

    /**
     * Set dob
     *
     * @param \DateTime $dob
     * @return Members
     */
    public function setDob($dob) {
        $this->dob = $dob;

        return $this;
    }

    /**
     * Get dob
     *
     * @return \DateTime 
     */
    public function getDob() {
        return $this->dob;
    }

    /**
     * Set gender
     *
     * @param string $gender
     * @return Members
     */
    public function setGender($gender) {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string 
     */
    public function getGender() {
        return $this->gender;
    }

    /**
     * Set user_id
     *
     * @param integer $userId
     * @return Members
     */
    public function setUserId($userId) {
        $this->user_id = $userId;

        return $this;
    }

    /**
     * Get user_id
     *
     * @return integer 
     */
    public function getUserId() {
        return $this->user_id;
    }

    /**
     * Set country_id
     *
     * @param integer $countryId
     * @return Members
     */
    public function setCountryId($countryId) {
        $this->country_id = $countryId;

        return $this;
    }

    /**
     * Get country_id
     *
     * @return integer 
     */
    public function getCountryId() {
        return $this->country_id;
    }

    /**
     * Set location_id
     *
     * @param integer $locationId
     * @return Members
     */
    public function setLocationId($locationId) {
        $this->location_id = $locationId;

        return $this;
    }

    /**
     * Get location_id
     *
     * @return integer 
     */
    public function getLocationId() {
        return $this->location_id;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Members
     */
    public function setCity($city) {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity() {
        return $this->city;
    }

    /**
     * Set inviter_id
     *
     * @param integer $inviterId
     * @return Members
     */
    public function setInviterId($inviterId) {
        $this->inviter_id = $inviterId;

        return $this;
    }

    /**
     * Get inviter_id
     *
     * @return integer 
     */
    public function getInviterId() {
        return $this->inviter_id;
    }

    /**
     * Set id_passport
     *
     * @param string $idPassport
     * @return Members
     */
    public function setIdPassport($idPassport) {
        $this->id_passport = $idPassport;

        return $this;
    }

    /**
     * Get id_passport
     *
     * @return string 
     */
    public function getIdPassport() {
        return $this->id_passport;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Members
     */
    public function setPhone($phone) {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone() {
        return $this->phone;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Members
     */
    public function setAddress($address) {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress() {
        return $this->address;
    }

    /**
     * Set points
     *
     * @param integer $points
     * @return Members
     */
    public function setPoints($points) {
        $this->points = $points;

        return $this;
    }

    /**
     * Get points
     *
     * @return integer 
     */
    public function getPoints() {
        return $this->points;
    }

    /**
     * Get created_by
     *
     * @return integer 
     */
    public function getCreatedBy() {
        return $this->created_by;
    }

    /**
     * Get date_created
     *
     * @return \DateTime 
     */
    public function getDateCreated() {
        return $this->date_created;
    }

    /**
     * Get modified_by
     *
     * @return integer 
     */
    public function getModifiedBy() {
        return $this->modified_by;
    }

    /**
     * Get date_modified
     *
     * @return \DateTime 
     */
    public function getDateModified() {
        return $this->date_modified;
    }

}
