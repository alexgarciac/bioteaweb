<?php

namespace Bioteawebapi\Entities;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;

/**
 * Annotation Entity represents Indexes for a Biotea Annotation
 * 
 * @Entity
 */
class Annotation
{
    /** 
     * @var int
     * @Id @GeneratedValue @Column(type="integer") 
     **/
    protected $id;

    /**
     * @var Term
     * @ManyToOne(targetEntity="Term", inversedBy="annotations")
     **/
    protected $term;

    /**
     * @var Document
     * @ManyToOne(targetEntity="Document", inversedBy="annotations")
     */
    protected $document;

    // --------------------------------------------------------------

    /**
     * Constructor
     *
     * @param Term $term
     */
    public function __construct(Term $term)
    {
        $this->term = $term;
    }

    // --------------------------------------------------------------

    public function __get($val)
    {
        return $this->$val;
    }

    // --------------------------------------------------------------

    public function getId()
    {
        return $this->id;
    }

    // --------------------------------------------------------------

    public function getTerm()
    {
        return $this->term;
    }

    // --------------------------------------------------------------

    public function getDocument()
    {
        return $this->document;
    }
}

/* EOF: Document.php */