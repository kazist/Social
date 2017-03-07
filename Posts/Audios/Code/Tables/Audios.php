<?php

namespace Social\Posts\Audios\Code\Tables;

use Doctrine\ORM\Mapping as ORM;

/**
 * Audios
 *
 * @ORM\Table(name="social_posts_audios", indexes={@ORM\Index(name="post_id_index", columns={"post_id"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Audios extends \Kazist\Table\BaseTable {

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
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(name="file", type="string", length=255, nullable=false)
     */
    protected $file;

    /**
     * @var integer
     *
     * @ORM\Column(name="media_id", type="integer", length=11, nullable=true)
     */
    protected $media_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="post_id", type="integer", length=11, nullable=true)
     */
    protected $post_id;

    /**
     * @var string
     *
     * @ORM\Column(name="embed_code", type="text", nullable=true)
     */
    protected $embed_code;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
    protected $type;

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
     * Set title
     *
     * @param string $title
     * @return Audios
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set file
     *
     * @param string $file
     * @return Audios
     */
    public function setFile($file) {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return string 
     */
    public function getFile() {
        return $this->file;
    }

    /**
     * Set media_id
     *
     * @param integer $mediaId
     * @return Audios
     */
    public function setMediaId($mediaId) {
        $this->media_id = $mediaId;

        return $this;
    }

    /**
     * Get media_id
     *
     * @return integer 
     */
    public function getMediaId() {
        return $this->media_id;
    }

    /**
     * Set post_id
     *
     * @param integer $postId
     * @return Audios
     */
    public function setPostId($postId) {
        $this->post_id = $postId;

        return $this;
    }

    /**
     * Get post_id
     *
     * @return integer 
     */
    public function getPostId() {
        return $this->post_id;
    }

    /**
     * Set embed_code
     *
     * @param string $embedCode
     * @return Audios
     */
    public function setEmbedCode($embedCode) {
        $this->embed_code = $embedCode;

        return $this;
    }

    /**
     * Get embed_code
     *
     * @return string 
     */
    public function getEmbedCode() {
        return $this->embed_code;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Audios
     */
    public function setType($type) {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType() {
        return $this->type;
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
