<?php
/************************************************************************
/ Class Population : Genetic Algorithms 
/
/************************************************************************/

require_once('individual.php');  //supporting class file

 class Population {

	//class properties
	public $people=array();
	
    /*
     * Constructors
     */
    // Create a population
     function __construct($populationSize,$initialise=false) 
	 {
		 
		 if (!isset($populationSize) || $populationSize==0)
			  die("Must specify a populationsize > 0");
        
		for ($i=0;$i<$populationSize; $i++)
			$this->people[$i] = new individual();  //instantiate a new object
		
        // Initialise population
        if ($initialise) 
		 {
            // Loop and create individuals
            for ($i = 0; $i < count($this->people); $i++) {
                $new_person = new individual();
                $new_person->generateIndividual(count(fitnesscalc::$solution) );
                $this->saveIndividual($i, $new_person );
            }
        }
    }

    /* Getters */
	
	/* find the fittest individual in this population */
    public function getFittest() {
        $fittest = $this->people[0];  //create a starting point for fitness person0
		
        // Loop through individuals to find fittest
        for ($i = 0; $i < $this->size(); $i++) {
            if ($fittest->getFitness() >= $this->people[$i]->getFitness() ) {
                $fittest = $this->people[$i];
				//echo "\nPopulation:getFittest() is now: ".$this->people[$i]->getFitness();
            }
			
        }
		 
        return $fittest;
    }

    /* Public methods */
	   // get individual
    public function getIndividual($index) {
      return  $this->people[$index];
    }
	
    // Get population size
    public function size() {
        return count($this->people);
    }

    // Save individual
    public function saveIndividual($index, $indiv) {
        $this->people[$index] = $indiv;
    }
	
	
	// Sort the pool based on fitness ascending form 0...max_fitness
	// Fitness here is a cost function so lower is better fitness
	function  compareFitness($a, $b) { 
    if($a->getFitness() == $b->getFitness() ) {
        return 0;
    } 
    return ($a->getFitness() < $b->getFitness()) ? -1 : 1;
	}

   //sort Population by fitness	 , most fit (lowest cost first)	
	function sortPopulation()
	{
     	return usort($this->people,array('population',"compareFitness")     );
	}

	
	//print population and fitness for debugging uses
	 public function __toString() {
       $population_string=null;
        for ($i = 0; $i <  count($this->people); $i++) {
       $population_string.="\n Individual: ".$this->people[$i]." Fitness:".$this->people[$i]->getFitness();
        }
	
		return $population_string;
	      
		}
		
		

  } //end class
?>