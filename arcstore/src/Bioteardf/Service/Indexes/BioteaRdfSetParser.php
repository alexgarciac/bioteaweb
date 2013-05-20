<?php

namespace Bioteardf\Service\Indexes;

use Bioteardf\Exception\BioteaRdfParseException;
use Bioteardf\Model\BioteaRdfSet;
use Bioteardf\Model\Doc;
use SimpleXMLElement, Exception;

/**
 * Parses Biotea RDF Sets into a graph of Doc Objects
 */
class BioteaRdfSetParser
{
    /**
     * @var Bioteardf\Service\Indexes\MainDocParser
     */
    private $mainDocParser;

    /**
     * @var Bioteardf\Service\Indexes\AnnotationSetParser
     */
    private $annotParser;

    // --------------------------------------------------------------

    /**
     * Constructor
     *
     * @param array   $vocabList  Key/value vocabulary list [shortname => full URI, etc.]
     */
    public function __construct(MainDocParser $mainDocParser, AnnotationSetParser $annotParser)
    {
        $this->mainDocParser = $mainDocParser;
        $this->annotParser   = $annotParser;
    }

    // --------------------------------------------------------------

    /**
     * Analyze a set and return its terms/topics/vocabularies set
     *
     * @param  Bioteardf\Model\BioteaRdfSet $rdfSet  RDF Set to analyze
     * @return Bioteardf\Model\Doc\Document
     */
    public function analyzeSet(BioteaRdfSet $rdfSet)
    {
        //Derive PMID from the filename
        $pmid = substr($rdfSet->mainFile->getBaseName('.' . $rdfSet->mainFile->getExtension()), 3);

        //Build a DocObj
        $docObj = new Doc\Document($pmid);

        //Parse Main File
        $this->mainDocParser->parseFile($rdfSet->mainFile, $docObj);

        //Parse Annotation File
        foreach($rdfSet->annotationFiles as $annotFile) {
            $this->annotParser->parseFile($annotFile, $docObj);
        }

        return $docObj;
    }

    // --------------------------------------------------------------

    /**
     * Setup namespaces for XML
     *
     * @param  SimpleXMLElement $xml
     * @return SimpleXmlElement
     */
    // private function setupNs(SimpleXMLElement $xml)
    // {
    //     $xml->registerXPathNamespace('ao', 'http://purl.org/ao/core/');
    //     $xml->registerXPathNamespace('rdf', 'http://www.w3.org/1999/02/22-rdf-syntax-ns#');
    //     $xml->registerXPathNamespace('rdfs', 'http://www.w3.org/2000/01/rdf-schema#');
    //     $xml->registerXPathNamespace('owl', 'http://www.w3.org/2002/07/owl#');
    //     $xml->registerXPathNamespace('bibo', 'http://purl.org/ontology/bibo/');
    //     $xml->registerXPathNamespace('doco', 'http://purl.org/spar/doco/');
    //     $xml->registerXPathNamespace('sioc', 'http://rdfs.org/sioc/ns#');
    //     $xml->registerXPathNamespace('foaf', 'http://xmlns.com/foaf/0.1/');
    //     $xml->registerXPathNamespace('dcterms', 'http://purl.org/dc/terms/');
    //     $xml->registerXPathNamespace('xsp', 'http://www.owl-ontologies.com/2005/08/07/xsp.owl');
    //     $xml->registerXPathNamespace('cnt', 'http://www.w3.org/2011/content#');
    //     $xml->registerXPathNamespace('prov', 'http://www.w3.org/ns/prov#');

    //     return $xml;
    // } 
}

/* EOF: BioteaRdfSetParser.php */