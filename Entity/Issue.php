<?php

namespace Smith981\NewsboxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Issue
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Issue
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(name="image_url", type="text", nullable=true)
     */
    private $imageUrl;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="published", type="datetime", nullable=true)
     */
    private $publishedDate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    private $status;

    /**
     * @var array
     *
     * @ORM\OneToMany(targetEntity="Story", mappedBy="issue")
     */
    private $stories;

    public function __construct() {
        $this->stories = new ArrayCollection();
    }

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
     * Set created
     *
     * @param \DateTime $created
     * @return Issue
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set imageUrl
     *
     * @param string $imageUrl
     * @return Issue
     */
    public function setImageUrl($imageUrl)
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    /**
     * Get imageUrl
     *
     * @return string
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * Set publishedDate
     *
     * @param \DateTime $publishedDate
     * @return Issue
     */
    public function setPublishedDate($publishedDate)
    {
        $this->publishedDate = $publishedDate;

        return $this;
    }

    /**
     * Get publishedDate
     *
     * @return \DateTime 
     */
    public function getPublishedDate()
    {
        return $this->publishedDate;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Issue
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Add stories
     *
     * @param \Smith981\NewsboxBundle\Entity\Story $stories
     * @return Issue
     */
    public function addStory(\Smith981\NewsboxBundle\Entity\Story $stories)
    {
        $this->stories[] = $stories;

        return $this;
    }

    /**
     * Remove stories
     *
     * @param \Smith981\NewsboxBundle\Entity\Story $stories
     */
    public function removeStory(\Smith981\NewsboxBundle\Entity\Story $stories)
    {
        $this->stories->removeElement($stories);
    }

    /**
     * Get stories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getStories()
    {
        return $this->stories;
    }

    public function __toString() 
    {
        return 'Issue ' . $this->getId();
    }
}
