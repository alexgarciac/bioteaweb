<?php

namespace Bioteardf\Model\Doc;

use Doctrine\Common\Collections\ArrayCollection;
use Bioteardf\Helper\DocIndexEntity;

/**
 * Annotation
 * 
 * @Entity
 */
class Annotation extends DocIndexEntity
{   
    /**
     * @var Term
     * @ManyToOne(targetEntity="Term", inversedBy="annotations")
     */    
    protected $term;

    /**
     * @var ArrayCollection
     * @OneToMany(targetEntity="TermInstance", mappedBy="paragraph")
     */
    protected $termInstances;

    /**
     * @var string
     * @Column(type="text") 
     */
    protected $identifier;

    // --------------------------------------------------------------

    public function __construct($identifier, Term $term)
    {
        $this->identifier    = $identifier;
        $this->termInstances = new ArrayCollection();
        $this->term          = $term;
        
        $this->locallyUniqueId = hash('sha256', strtolower($identifier));
    }

    // ----------------------------------------------------------------

    public function __tostring()
    {
        return $this->identifier;
    }

    // --------------------------------------------------------------

    public function addTermInstance(TermInstance $termInstance)
    {
        $this->termInstances[] = $termInstance;
    }            
}

/* EOF: Annotation.php */