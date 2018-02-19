<?php
/************************************************************************
/ Class fitnesscalc : Genetic Algorithms 
/
/************************************************************************/

require_once('individual.php');  //supporting class file

 class fitnesscalc {

    public static  $solution =  array();  //empty array of arbitrary length

    /* Public methods */
    // Set a candidate solution as a byte array
  
    // To make it easier we can use this method to set our candidate solution with string of 0s and 1s
    static function setSolution($newSolution) {
		
         // Loop through each character of our string and save it in our string  array
		 fitnesscalc::$solution=str_split($newSolution);
		// print_r(fitnesscalc::$solution);
		

    }

    // Calculate individuals fitness by comparing it to our candidate solution
	// low fitness values are better,0=goal fitness is really a cost function in this instance
    static function  getFitness($individual) {
       $fitness = 0;
	   $sol_count=count(fitnesscalc::$solution);  /* get array size */
	   
        // Loop through our individuals genes and compare them to our candidates
        for ($i=0; $i < $individual->size() && $i < $sol_count; $i++ )
		{
			$char_diff=0;
			//compare genes and note the difference using ASCII value vs. solution Ascii value note the difference
            $char_diff=abs( ord($individual->getGene($i)) - ord(fitnesscalc::$solution[$i]) ) ; 
			//$char_fitness=($individual->getGene($i)==fitnesscalc::$solution[$i])?1:0; //if exact match add 1 to fitness 
				$fitness+=$char_diff; // low fitness values are better,
        }
		
		//echo "Fitness: $fitness";
		
        return $fitness;  //inverse of cost function
		
    }
    
    // Get optimum fitness
    static function getMaxFitness() {
        $maxFitness = 0; //maximum matches assume each exact charaters yields fitness 1
        return $maxFitness;
    }
	
	
	
	
}  //end class



?>