<?php

namespace Social\Posts\Videos\Code\Tables;

use Doctrine\ORM\Mapping as ORM;

/**
 * Videos
 *
 * @ORM\Table(name="social_posts_videos", indexes={@ORM\Index(name="post_id_index", columns={"post_id"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Videos extends \Kazist\Table\BaseTable {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="post_id", type="integer", length=11, nullable=false)
     */
    protected $post_id;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=false)
     */
    protected $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="image", type="integer", length=11, nullable=true)
     */
    protected $image;

    /**
     * @var integer
     *
     * @ORM\Column(name="file", type="integer", length=11, nullable=true)
     */
    protected $file;

    /**
     * @var string
     *
     * @ORM\Column(name="youtube_code", type="string", length=255, nullable=true)
     */
    protected $youtube_code;

    /**
     * @var string
     *
     * @ORM\Column(name="embed_code", type="text", nullable=true)
     */
    protected $embed_code;

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
     * Set post_id
     *
     * @param integer $postId
     * @return Videos
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
     * Set type
     *
     * @param string $type
     * @return Videos
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
     * Set image
     *
     * @param integer $image
     * @return Videos
     */
    public function setImage($image) {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return integer 
     */
    public function getImage() {
        return $this->image;
    }

    /**
     * Set file
     *
     * @param integer $file
     * @return Videos
     */
    public function setFile($file) {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return integer 
     */
    public function getFile() {
        return $this->file;
    }

    /**
     * Set youtube_code
     *
     * @param string $youtubeCode
     * @return Videos
     */
    public function setYoutubeCode($youtubeCode) {
        $this->youtube_code = $youtubeCode;

        return $this;
    }

    /**
     * Get youtube_code
     *
     * @return string 
     */
    public function getYoutubeCode() {
        return $this->youtube_code;
    }

    /**
     * Set embed_code
     *
     * @param string $embedCode
     * @return Videos
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
