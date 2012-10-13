<?php

namespace Bioteawebapi\Entities;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Annotation Entity represents Indexes for a Biotea Annotation
 * 
 * @Entity
 */
class Term
{
     /** @Id @GeneratedValue @Column(type="integer") */
    protected $id;

    /** @Column(type="string") **/
    protected $term;

    /**
     * @ManyToMany(targetEntity="Topic", mappedBy="terms")
     **/
    private $topics;

    /**
     * @OneToMany(targetEntity="Annotation", mappedBy="term")
     **/    
    private $annotations;

    // --------------------------------------------------------------

    /**
     * Constructor
     *
     * @param string $term
     * @param array $topics  Array of topic objects
     */
    public function __construct($term, Array $topics = array())
    {
        $this->annotations = new ArrayCollection();
        $this->topics = new ArrayCollection();

        //Set the term
        $this->term = $term;

        //Set the topics
        foreach($topics as $topic) {
            $this->addTopic($topic);
        }
    }

    // --------------------------------------------------------------

    public function __toString()
    {
        return $this->getTerm();
    }

    // --------------------------------------------------------------

    public function getTerm()
    {
        return $this->term;
    }

    // --------------------------------------------------------------

    public function getAnnotations()
    {
        return $this->annotations;
    }

    // --------------------------------------------------------------

    public function getTopics()
    {
        return $this->topics;
    }

    // --------------------------------------------------------------

    /**
     * Add a topic to this term
     *
     * @param Topic $topic
     */
    public function addTopic(Topic $topic)
    {
        $this->topics[] = $topic;
    }
}


/* EOF: Term.php */