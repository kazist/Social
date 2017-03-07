<?php

namespace Social\Posts\Code\Tables;

use Doctrine\ORM\Mapping as ORM;

/**
 * Posts
 *
 * @ORM\Table(name="social_posts", indexes={@ORM\Index(name="user_id_index", columns={"user_id"}), @ORM\Index(name="post_id_index", columns={"post_id"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Posts extends \Kazist\Table\BaseTable
{
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
     * @ORM\Column(name="user_id", type="integer", length=11, nullable=false)
     */
    protected $user_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="post_id", type="integer", length=11, nullable=true)
     */
    protected $post_id;

    /**
     * @var string
     *
     * @ORM\Column(name="post", type="text", nullable=false)
     */
    protected $post;

    /**
     * @var string
     *
     * @ORM\Column(name="sharing", type="text", nullable=true)
     */
    protected $sharing;

    /**
     * @var string
     *
     * @ORM\Column(name="privacy", type="string", length=255, nullable=true)
     */
    protected $privacy;

    /**
     * @var string
     *
     * @ORM\Column(name="media", type="text", nullable=true)
     */
    protected $media;

    /**
     * @var integer
     *
     * @ORM\Column(name="created_by", type="integer", length=11, nullable=true)
     */
    protected $created_by;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_created", type="datetime", nullable=true)
     */
    protected $date_created;

    /**
     * @var integer
     *
     * @ORM\Column(name="modified_by", type="integer", length=11, nullable=true)
     */
    protected $modified_by;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_modified", type="datetime", nullable=true)
     */
    protected $date_modified;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user_id
     *
     * @param integer $userId
     * @return Posts
     */
    public function setUserId($userId)
    {
        $this->user_id = $userId;

        return $this;
    }

    /**
     * Get user_id
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Set post_id
     *
     * @param integer $postId
     * @return Posts
     */
    public function setPostId($postId)
    {
        $this->post_id = $postId;

        return $this;
    }

    /**
     * Get post_id
     *
     * @return integer 
     */
    public function getPostId()
    {
        return $this->post_id;
    }

    /**
     * Set post
     *
     * @param string $post
     * @return Posts
     */
    public function setPost($post)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post
     *
     * @return string 
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Set sharing
     *
     * @param string $sharing
     * @return Posts
     */
    public function setSharing($sharing)
    {
        $this->sharing = $sharing;

        return $this;
    }

    /**
     * Get sharing
     *
     * @return string 
     */
    public function getSharing()
    {
        return $this->sharing;
    }

    /**
     * Set privacy
     *
     * @param string $privacy
     * @return Posts
     */
    public function setPrivacy($privacy)
    {
        $this->privacy = $privacy;

        return $this;
    }

    /**
     * Get privacy
     *
     * @return string 
     */
    public function getPrivacy()
    {
        return $this->privacy;
    }

    /**
     * Set media
     *
     * @param string $media
     * @return Posts
     */
    public function setMedia($media)
    {
        $this->media = $media;

        return $this;
    }

    /**
     * Get media
     *
     * @return string 
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * Get created_by
     *
     * @return integer 
     */
    public function getCreatedBy()
    {
        return $this->created_by;
    }

    /**
     * Get date_created
     *
     * @return \DateTime 
     */
    public function getDateCreated()
    {
        return $this->date_created;
    }

    /**
     * Get modified_by
     *
     * @return integer 
     */
    public function getModifiedBy()
    {
        return $this->modified_by;
    }

    /**
     * Get date_modified
     *
     * @return \DateTime 
     */
    public function getDateModified()
    {
        return $this->date_modified;
    }
    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        // Add your code here
    }
}
