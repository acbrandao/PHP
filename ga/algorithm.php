<?php
/************************************************************************
/ Class geneticAlgorithm : Genetic Algorithms 
/
/************************************************************************/


require_once('individual.php');  //supporting class file
require_once('population.php');  //supporting class file

class algorithm {

    /* GA parameters */
  public static $uniformRate=0.5;  /* crosssover determine what where to break gene string */
  public static $mutationRate=0.20; /* When choosing which genes to mutate what rate of random values are mutated */
  public static $poolSize=10;  /* When selecting for crossover how large each pool should be */
  public static $max_generation_stagnant=200;  /*how many unchanged generations before we end */
  public static $elitism=true;

    /* Public methods */
    
    // Convenience random function
 private static function random() {
  return (float)rand()/(float)getrandmax();  /* return number from 0 .. 1 as a decimal */
}
	
    public static function evolvePopulation( $pop) {
       $newPopulation = new population($pop->size(), false);

	  
        // Keep our best individual
        if (algorithm::$elitism) {
            $newPopulation->saveIndividual(0, $pop->getFittest());
	    }

        // Crossover population
        $elitismOffset=0;
        if (algorithm::$elitism) {
            $elitismOffset = 1;
        } else {
            $elitismOffset = 0;
        }
		
        // Loop over the population size and create new individuals with
        // crossover
	
        for ($i = $elitismOffset; $i < $pop->size(); $i++) 
		{	 
            $indiv1 = algorithm::poolSelection($pop);
            $indiv2 = algorithm::poolSelection($pop);
            $newIndiv =  algorithm::crossover($indiv1, $indiv2);
            $newPopulation->saveIndividual($i, $newIndiv);
        }

        // Mutate population
	
        for ($i=$elitismOffset; $i < $newPopulation->size(); $i++) {
            algorithm::mutate($newPopulation->getIndividual($i));
        }

	
        return $newPopulation;
    }

    // Crossover individuals (aka reproduction)
    private static function  crossover($indiv1, $indiv2) 
	 {
       $newSol = new individual();  //create a offspring
        // Loop through genes
        for ($i=0; $i < $indiv1->size(); $i++) {
            // Crossover at which point 0..1 , .50 50% of time
            if (  algorithm::random() <= algorithm::$uniformRate)
			{
                $newSol->setGene($i, $indiv1->getGene($i) );
            } else {
                $newSol->setGene($i, $indiv2->getGene($i));
            }
        }
        return $newSol;
    }

    // Mutate an individual
    private static function mutate( $indiv) {
        // Loop through genes
        for ($i=0; $i < $indiv->size(); $i++) {
            if (  algorithm::random() <= algorithm::$mutationRate) {
                $gene = individual::$characters[rand(0, strlen(individual::$characters) - 1)];    // Create random gene
                $indiv->setGene($i, $gene); //substitute the gene into the individual
            }
        }
    }

    // Select a pool of individuals for crossover
    private static function poolSelection($pop) {
        // Create a pool population
        $pool = new population(algorithm::$poolSize, false);
	
	   for ($i=0; $i < algorithm::$poolSize; $i++) {
	     $randomId = rand(0, $pop->size()-1 ); //Get a random individual from anywhere in the population
			$pool->saveIndividual($i, $pop->getIndividual( $randomId));

        }
        // Get the fittest
        $fittest = $pool->getFittest();
        return $fittest;
    }


	}  //class
?>