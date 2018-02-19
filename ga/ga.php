<?php
/************************************************************************
/ GA : Genetic Algorithms  main page
/
/************************************************************************/

require_once('individual.php');  //supporting individual 
require_once('population.php');  //supporting population 
require_once('fitnesscalc.php');  //supporting fitnesscalc 
require_once('algorithm.php');  //supporting fitnesscalc 

$solution_phrase="A genetic algorithm found!";
algorithm::$uniformRate=0.50;
algorithm::$mutationRate=0.05;
algorithm::$poolSize=15; /* crossover how many to select in each pool to breed from */
$initial_population_size=45;		//how many random individuals are in initial population (generation 0)
algorithm::$max_generation_stagnant=400;  //maximum number of unchanged generations terminate loop
algorithm::$elitism=true;  //keep fittest individual  for next gen

$lowest_time_s=100.00; //keeps track of lowest time in seconds

$generationCount = 0;
$generation_stagnant=0; 
$most_fit=0;
$most_fit_last=400;

echo "\n ________                      __  .__        ";
echo "\n/  _____/  ____   ____   _____/  |_|__| ____  ";
echo "\n/   \  ____/ __ \ /    \_/ __ \   __\  |/ ___\ ";
echo "\n\    \_\  \  ___/|   |  \  ___/|  | |  \  \___ ";
echo "\n\______  /\___  >___|  /\___  >__| |__|\___  >";
echo "\n       \/     \/     \/     \/             \/ ";

echo "\n-----------------------------------------------";
echo "\nUniformRate (crosssover point  where to break gene string) :".algorithm::$uniformRate;
echo "\nmutationRate (what % of genes change for each mutate) :".algorithm::$mutationRate;
echo "\nPoolSize (crossover # of  individuals to select in each pool ):".algorithm::$poolSize;
echo "\nInitial population # individuals:".$initial_population_size;
echo "\nelitism (keep best individual each generation true=1) :".algorithm::$elitism;
  
  
        // Set a candidate solution static class
        fitnesscalc::setSolution($solution_phrase);
		
echo "\nMax Fitness is :".fitnesscalc::getMaxFitness();
echo "\n-----------------------------------------------";
		
		
        // Create an initial population
		$time1 = microtime(true);
       $myPop = new population($initial_population_size, true);
        
        // Evolve our population until we reach an optimum solution
		
        while ($myPop->getFittest()->getFitness() > fitnesscalc::getMaxFitness())
 			{
            $generationCount++;
			$most_fit=$myPop->getFittest()->getFitness();
          
		   $myPop = algorithm::evolvePopulation($myPop); //create a new generation
		   
		   if ($most_fit < $most_fit_last)
		   {
			// echo " *** MOST FIT ".$most_fit." Most fit last".$most_fit_last;
			 echo "\n Generation: " .$generationCount." (Stagnant:".$generation_stagnant.") Fittest: ". $most_fit."/".fitnesscalc::getMaxFitness() ;
			 echo "  Best: ". $myPop->getFittest();
			   $most_fit_last=$most_fit;
			   $generation_stagnant=0; //reset stagnant generation counter
		   }
		   else
		     $generation_stagnant++; //no improvement increment may want to end early
		 
		  if ( $generation_stagnant > algorithm::$max_generation_stagnant)
		  {
			  echo "\n-- Ending TOO MANY (".algorithm::$max_generation_stagnant.") stagnant generations unchanged. Ending APPROX solution below \n..)";
		      break;
		  }
			
        }  //end of while loop
		
		//we're done
		$time2 = microtime(true);
		
		
        echo "\nSolution at generation: ".$generationCount. " time: ".round($time2-$time1,2)."s";
		echo "\n---------------------------------------------------------\n";
		echo "\nGenes   : ".$myPop->getFittest() ;
		echo "\nSolution: ".implode("",fitnesscalc::$solution);  //convert array to string
		echo "\n---------------------------------------------------------\n";
		
		

?>
