<?php

namespace Bioteawebapi\Entities;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;

/**
 * Vocabulary Entity
 * 
 * @Entity
 * @Table(uniqueConstraints={
 *   @UniqueConstraint(name="uri", columns={"uri"}),
 *   @UniqueConstraint(name="shortName", columns={"shortName"})
 * })
 */
class Vocabulary
{
    /** @Id @GeneratedValue @Column(type="integer") **/
    protected $id;

    /** @Column(type="string") **/
    protected $uri;

    /** @Column(type="string", nullable=true) **/
    protected $shortName;

    /**
     * @OneToMany(targetEntity="Topic", mappedBy="vocabulary")
     **/
    private $topics;

    // --------------------------------------------------------------

    /**
     * Constructor
     *
     * @param string $uri
     * @param string $shortName
     */
    public function __construct($uri, $shortName = null)
    {
        $this->topics = new ArrayCollection();

        //Set parameters
        $this->uri = $uri;
        $this->shortName = $shortName;
    }

    // --------------------------------------------------------------

    public function __get($val)
    {
        return $this->$val;
    }
    
    // --------------------------------------------------------------

    public function __toString()
    {
        return $this->getUri();
    }

    // --------------------------------------------------------------

    public function getId()
    {
        return $this->id;
    }
    
    // --------------------------------------------------------------

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    // --------------------------------------------------------------

    /**
     * @return string
     */
    public function getShortName()
    {
        return (string) $shortName;
    }
}

/* EOF: Vocabulary.php */