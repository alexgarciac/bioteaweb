<?php

namespace Bioteardf\Model\Doc;

use Doctrine\Common\Collections\ArrayCollection;
use Bioteardf\Helper\DocIndexEntity;

/**
 * Vocabulary
 * 
 * @Entity
 * @Table(uniqueConstraints={
 *   @UniqueConstraint(name="uri", columns={"uri"}),
 *   @UniqueConstraint(name="shortName", columns={"shortName"})
 * })   
 */
class Vocabulary extends DocIndexEntity
{    
    /**
     * @var string
     * @Column(type="string") 
     */
    protected $uri;

    /**
     * @var string
     * @Column(type="string") 
     */
    protected $shortName;

    /**
     * @var ArrayCollection
     * @OneToMany(targetEntity="Topic", mappedBy="vocabulary")     
     */
    protected $topics;

    // --------------------------------------------------------------

    public function __construct($uri, $shortName)
    {
        $this->uri       = $uri;
        $this->shortName = $shortName;
        $this->topics    = new ArrayCollection();

        $this->locallyUniqueId = strtolower((string) $this);
    }

    // ----------------------------------------------------------------

    public function __tostring()
    {
        return $this->uri;
    }

    // --------------------------------------------------------------

    public function addTopic(Topic $topic)
    {
        $this->topics[] = $topic;
    }

}

/* EOF: Vocabulary.php */