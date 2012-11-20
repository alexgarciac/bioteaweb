
Biojs.ResultsGraph=Biojs.Graph.extend({constructor:function(options){this.base();},opt:{target:"YourOwnDivId",sparqlUrl:'http://virtuoso.idiginfo.org/sparql',proxyUrl:'../biojs/dependencies/proxy/proxy.php',minimumTermFrequency:30},eventTypes:["onClick","onQueryRequest","onDataArrived"],_nodesByTerm:{},setQuery:function(userQuery){var self=this;this._showLoading();this.removeAllNodes();this._removeData();this._requestUniprotAccession(userQuery,function(accession){if(!Biojs.Utils.isEmpty(accession)&&accession.match(/^[A-z]([0-9])*$/g)){var strQuery='SELECT ?article ?title ?seeAlso ?sameAs ?date ?abstract ?journalTitle ?volume ?issue ?start ?end fn:concat(?authorLastName, ", ", ?authorFirstName) AS ?author '+'WHERE {'+'?article a bibo:AcademicArticle ; '+'dcterms:source ?pmc ; '+'dcterms:title ?title ; '+'dcterms:publisher ?publisher ; '+'bibo:volume ?volume ; '+'bibo:pageStart ?start ; '+'bibo:pageEnd ?end ; '+'bibo:authorList ?authorList ; '+'bibo:abstract ?abstract . '+'?pmc owl:sameAs ?sameAs . '+'OPTIONAL {?pmc rdfs:seeAlso ?seeAlso} . '+'OPTIONAL {?article dcterms:issued ?date } . '+'?authorList rdfs:member ?member . '+'?member foaf:givenName ?authorFirstName ; '+'foaf:familyName ?authorLastName. '+'?article dcterms:hasPart ?issueJournal . '+'?issueJournal a bibo:Issue ; '+'dcterms:hasPart ?journal . '+'OPTIONAL {?issueJournal bibo:issue ?issue } . '+'?journal a bibo:Journal ; '+'dcterms:title ?journalTitle . '+'?annot a aot:ExactQualifier ; '+'pav:createdBy <http://www.ebi.ac.uk/webservices/whatizit#whatizitUkPmcAll> ; '+'ao:annotatesResource ?article ; '+'ao:hasTopic <http://purl.uniprot.org/core/'+accession+'> . '+'} LIMIT 100';Biojs.console.log("Applying SPARQL query: "+strQuery);self._doQuery(strQuery);}else{}});},_showLoading:function(){},_doQuery:function(query){var params={query:query,format:'application/json'};this._showLoading();this._fetchData(this.opt.sparqlUrl,params,function(data){var columns=Biojs.ResultsGraph.COLUMNS;var decodedData,results,obj,articles;var jsonData={iTotalRecords:0,iTotalDisplayRecords:0,aaData:[]};try{decodedData=jQuery.parseJSON(data);results=decodedData.results.bindings;}catch(e){Biojs.console.log("Error decoding response data: "+e.message);return jsonData;}
Biojs.console.log(decodedData);articles={};for(r in results){obj=results[r];if(!articles.hasOwnProperty(obj.title.value)){articles[obj.title.value]=new Array(columns.length);for(c=0;c<columns.length;c++){try{articles[obj.title.value][c]=obj[columns[c].key].value;}catch(e){if(c.optional){articles[obj.title.value][c]="";}else{articles[obj.title.value][c]="";Biojs.console.log("Missing value for column: "+c.name);}}}}else{for(c=0;c<columns.length;c++){if(!columns[c].unique){if(articles[obj.title.value][c].indexOf(obj[columns[c].key].value)==-1){articles[obj.title.value][c]+='; '+obj[columns[c].key].value;}}}}}
for(title in articles){jsonData.aaData.push(articles[title]);}
jsonData.iTotalRecords=jsonData.aaData.length;this._setData(jsonData);});},_setData:function(json){this._removeData();for(var i in json.aaData){this.addNode('<div id='+this._escape(json.aaData[i][0])+'>'+json.aaData[i][1]+'</div>');this._requestAnnotations(json.aaData[i][0]);}},_removeData:function(){delete this._nodesByTerm;this._nodesByTerm={};},_requestUniprotAccession:function(userQuery,fnCallback){var self=this;var query='SELECT distinct ?gene str(?uniprotAcc) as ?humanUniprotAcc '+'WHERE { '+'?wikiArticle rdf:type gw_wiki:Category-3AHuman_proteins ; '+'rdfs:label ?gene ; '+'gw_property:Has_entrez_id ?entrezId ; '+'gw_property:Has_uniprot_id ?uniprotAcc . '+'FILTER (regex(?gene, "'+userQuery+'", "i")) . '+'} LIMIT 1';var params={query:query,format:'application/json'};Biojs.console.log("Requesting the accession: "+query);this._fetchData(this.opt.sparqlUrl,params,function(data){var accession="";try{var jsonData=jQuery.parseJSON(data);accession=jsonData.results.bindings[0].humanUniprotAcc.value;}catch(e){Biojs.console.log("Error decoding response data: "+e.message);}
Biojs.console.log("Accession: "+accession);fnCallback.call(self,accession);});},_requestAnnotations:function(resourceURI,fnCallback){var self=this;query='SELECT '+'distinct ?annot fn:upper-case(str(?body)) AS ?term '+'?topic str(?init) AS ?posInit str(?end) AS ?posEnd ?comment '+'WHERE { '+'?annot a aot:ExactQualifier ; '+'ao:annotatesResource <'+resourceURI+'>; '+'ao:body ?body ; '+'ao:hasTopic ?topic ; '+'ao:context ?context . '+'?context rdfs:resource ?resource . '+'OPTIONAL {?context aos:init ?init }. '+'OPTIONAL {?context aos:end ?end } . '+'?resource cnt:chars ?comment .  '+'FILTER (regex(str(?topic), "(uniprot|GO|CHEBI|ICD9|UMLS|fma|MSH|PO|MDDB|NDDF|NDFRT|NCBITaxon)")) . } '+'ORDER BY ?term ?topic';var params={query:query,format:'application/json'};this._fetchData(this.opt.sparqlUrl,params,function(data){var result=[];var previousTerm="",currentTerm="",currentTopic="",previousTopic="";var term,i=0,currentComment="",topicObject;var dataBindings=[];try{var decodedData=jQuery.parseJSON(data);dataBindings=decodedData.results.bindings;}catch(e){Biojs.console.log("Error decoding response data: "+e.message);}
for(r in dataBindings){try{var annotation=dataBindings[r];currentTopic=annotation.topic.value;topicObject=Biojs.ResultsGraph.TOPIC[currentTopic.replace(/(#|_|:|\/)([A-Za-z0-9])*$/g,'')];currentTerm=annotation.term.value;if(topicObject.shortName&&currentTerm!=previousTerm){term={id:i,name:currentTerm,freq:0,topics:[],comments:[],used:false};result.push(term);i++;}
if(currentTopic!=previousTopic){term.topics.push({value:currentTopic,topic:topicObject});term.freq++;if(currentComment!=annotation.comment.value){term.comments.push([annotation]);currentComment=annotation.comment.value;}
if(!term.used&&term.freq>=this.opt.minimumTermFrequency){this.addTermToNode(resourceURI,term.name);term.used=true;}}
previousTerm=currentTerm;previousTopic=currentTopic;}catch(e){Biojs.console.log("Topic "+currentTopic+" not recognized. Error: "+e.message);}}});},_fetchData:function(sourceUrl,params,fnCallback){var self=this;var httpRequest={url:sourceUrl};httpRequest.dataType='json';if(this.opt.proxyUrl!=undefined){httpRequest.url=this.opt.proxyUrl;params=[{name:"url",value:sourceUrl+'?'+jQuery.param(params)}];httpRequest.dataType="text";}
httpRequest.success=function(data){fnCallback.call(self,data);self.raiseEvent(Biojs.ResultsGraph.EVT_ON_DATA_ARRIVED,{"data":data});}
httpRequest.type='GET';httpRequest.data=params;jQuery.ajax(httpRequest);},addTermToNode:function(resourceURI,term){var nodesByTerm;var nodeId=this._escape(resourceURI);if(!this._nodesByTerm.hasOwnProperty(term)){this._nodesByTerm[term]=[];}
nodesByTerm=this._nodesByTerm[term];for(var i in nodesByTerm){this.addLink(nodeId,nodesByTerm[i],term)}
nodesByTerm.push(nodeId);},_escape:function(str){return str.replace(/[^A-Za-z0-9\_]/gi,'');}},{EVT_ON_DATA_ARRIVED:"onDataArrived",COLUMNS:[{unique:true,optional:false,name:"Article",key:"article"},{unique:true,optional:false,name:"Title",key:"title"},],TOPIC:{"http://purl.obolibrary.org/obo/CHEBI":{shortName:"chebi",baseUrl:"http://identifiers.org/obo.chebi/CHEBI:",prefix:"CHEBI:",color:"#D6A100"},"http://purl.org/obo/owl/GO#GO":{shortName:"go",baseUrl:"http://identifiers.org/obo.go/GO:",prefix:"GO:",color:"#0067E6"},"http://purl.org/obo/owl/PW#PW":{shortName:"pw",baseUrl:"http://purl.org/obo/owl/PW#PW_",prefix:"",color:"#00DAE6"},"http://mged.sourceforge.net/ontologies/MGEDOntology.owl":{shortName:"mged",baseUrl:"http://mged.sourceforge.net/ontologies/MGEDOntology.owl#",prefix:"",color:"#6961FF"},"http://purl.uniprot.org/core":{shortName:"uniprot",baseUrl:"http://identifiers.org/uniprot/",prefix:"",color:"#61A8FF"},"http://purl.bioontology.org/ontology/MDDB":{shortName:"mddb",baseUrl:"http://purl.bioontology.org/ontology/MDDB/",prefix:"",color:"#FF61A8"},"http://purl.bioontology.org/ontology/NDDF":{shortName:"nddf",baseUrl:"http://purl.bioontology.org/ontology/NDDF/",prefix:"",color:"#C7005A"},"http://purl.bioontology.org/ontology/NDFRT":{shortName:"ndfrt",baseUrl:"http://purl.bioontology.org/ontology/NDFRT/",prefix:"",color:"#FF0F4B"},"http://purl.bioontology.org/ontology/MEDLINEPLUS":{shortName:"medline",baseUrl:"http://purl.bioontology.org/ontology/MEDLINEPLUS/",prefix:"",color:"#329D27"},"http://purl.bioontology.org/ontology/SNOMEDCT":{shortName:"snomed",baseUrl:"http://purl.bioontology.org/ontology/SNOMEDCT/",prefix:"",color:"#AAD864"},"http://purl.org/obo/owl/SYMP#SYMP":{shortName:"symptom",baseUrl:"http://purl.org/obo/owl/SYMP#SYMP_",prefix:"",color:"#A2E7BD"},"http://purl.bioontology.org/ontology/MDR":{shortName:"meddra",baseUrl:"http://purl.bioontology.org/ontology/MDR/",prefix:"",color:"#99CC00"},"http://purl.bioontology.org/ontology/MSH":{shortName:"mesh",baseUrl:"http://purl.bioontology.org/ontology/MSH/",prefix:"",color:"#669980"},"http://purl.bioontology.org/ontology/OMIM":{shortName:"omim",baseUrl:"http://identifiers.org/omim/",prefix:"",color:"#989966"},"http://purl.bioontology.org/ontology/ICD9-9":{shortName:"icd9",baseUrl:"http://identifiers.org/icd/",prefix:"",color:"#CABDAF"},"http://www.ifomis.org/bfo/1.1/span":{shortName:"obi",baseUrl:"http://identifiers.org/obo.obi/OBI_",prefix:"",color:"#806699"},"http://berkeleybop.org/obo/UMLS":{shortName:"umls",baseUrl:"http://berkeleybop.org/obo/UMLS:",prefix:"",color:"#CC00FF"},"http://purl.bioontology.org/ontology/PO":{shortName:"po",baseUrl:"http://purl.bioontology.org/ontology/PO/",prefix:"",color:"#007D00"},"http://ncicb.nci.nih.gov/xml/owl/EVS/Thesaurus.owl":{shortName:"ncithesaurus",baseUrl:"http://ncicb.nci.nih.gov/xml/owl/EVS/Thesaurus.owl",prefix:"",color:"#9C38FF"},"http://purl.org/obo/owl/NCBITaxon#NCBITaxon":{shortName:"ncbitaxon",baseUrl:"http://identifiers.org/taxonomy/",prefix:"",color:"#5E00BD"}}});