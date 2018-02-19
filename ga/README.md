# php Genetic Algorithm Sample code
Example of code expalined here  http://www.abrandao.com/2015/01/simple-php-genetic-algorithm/

## Installation
Simply clone  or download the few files that make this PHP Genetic algorith.  PHP files are based on PHP Classes with each class representing a portion of the genetic algorith code. The classes are broekn out as folllows :

 * **algorithm.php** Main class this controls and orchestrates the setup and configuration of the GA
 * **fitnessCalc.php**  class has the fittness calculation logic to determine which individuals are "fit"
 * **indiviudal.php**  class has the code for for setting up an individual composed of chromosones
 * **population.php**  class has the code for for managaing groups of  individuals

The other files are for providing an interfaces for the GA code to be run.
The other files are the ones that allow you to view the genetc algorthim with some sort of interface. Two types provided a web version..
 **ga_sse_demo.html**  : PRovides the web page to view a simple string interface
 **ga_sse_server.php** : Runs the GA algortihm on the server and sends via Server Side events results to ga_See_demo.html

 ga.php Provides command line running of genetic algortihm


## Usage

 Install all the above on a folder on your PHP supported web server.
 Simply visit the URL containing the example code  calling the example Server Side events demo such as :
http://www.abrandao.com/lab/ga/ga_sse_demo.html

To run from the command line, simple issue the follow from a system that has PHP installed and runs from the shell. 
`php ga.php`


## Contributing
1. Fork it!
2. Create your feature branch: `git checkout -b my-new-feature`
3. Commit your changes: `git commit -am 'Add some feature'`
4. Push to the branch: `git push origin my-new-feature`
5. Submit a pull request :D
## History
TODO: Write history
## Credits
TODO: Write credits
## License
TODO: Write license

