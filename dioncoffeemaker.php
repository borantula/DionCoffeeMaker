<?php


/*
*
*
DionCoffeeMaker
===============

Our poor coffee maker :(

We make it to brew some coffee in the office. 

It was originally developed by a ASP sympathizer PHP developer. So, it works but god knows whats wrong with it!

On every sip of coffee we drink, we feel it. So, fork it, send your comments about it and if you are in Istanbul and looking for a job as PHP developer, contact us.

Regards

Bora Yalçın - Dion Adworks
*
*
*/

abstract class Coffee {  
	//final coffee
	public $coffee;
	//bool
	public $milk; 
	//coffee type	
	public $coffee_type; 
	//milk type to use	
	public $milk_type; 
	//number of sugar cubes to use;	
	public $sugar;
	//surup to use with coffee
	public $surup;  
}  

interface CoffeeBean {
    
    public function Make_The_Coffee();
}


class DionCoffeeMaker extends Coffee implements CoffeeBean {
	

	//water temperature for coffee in celcius
	const WATER_TEMPERATURE = 94;

	public $errors = array();

	protected $valid_arguments = array(
									'coffee_type' => array('type'=>self::TYPE_STRING,'control_function'=>'coffee_type_check'),
									'milk'        => array('type'=>self::TYPE_BOOL,'control_function'=>''),
									'milk_type'   => array('type'=>self::TYPE_STRING,'control_function'=>'milk_type_check'),
									'sugar'       => array('type'=>self::TYPE_INTEGER,'control_function'=>''),
									'surup'       => array('type'=>self::TYPE_STRING,'control_function'=>'surup_type_check'),
									);


	protected $default_args = array(		
									'coffee_type' => 'dark',
									'milk'        => false,
									"milk_type"   => "regular",
									'sugar'       => 0,
									'surup'       => 'none',
								);


    	const TYPE_STRING = 'string';
    	const TYPE_BOOL = 'bool';
    	const TYPE_INTEGER = 'integer';

	function __construct( $args = array() ){

		$this->preperations($args);

		$this->Make_The_Coffee();
		
	}


	function preperations($args){
		//check for arg types
		$args = $this->validateArgTypes($args);

		//use control function
		foreach ($args as $k => $v)
		if( isset($this->valid_arguments[$k]['control_function']) && !empty($this->valid_arguments[$k]['control_function'])){

			$function = $this->valid_arguments[$k]['control_function'];
			
			if(!$this->$function($v)){
				unset($args[$k]);
			}
		}

		$args = array_merge($this->default_args,$args);



		foreach ($args as $argkey => $deger) {
			//class variables
			$this->$argkey = $deger;
		}
	}

	function validateArgTypes( $args = array()){
		//return if empty, don't even bother
		if(empty($args)) 
		{
			return $args;
		}


		$valid_arg_keys = array_keys( $this->valid_arguments );

		//this will be returned
		$args2 = array();

		
		foreach ($args as $argkey => $arg) {
			if(in_array($argkey,$valid_arg_keys)){

				$t = $this->valid_arguments[$argkey]['type'];



				if( self::TYPE_STRING == $t ) {

					if( is_string( $arg['type'] ) ) {
						$args2[$argkey] = $arg;
					}

				}else if( self::TYPE_BOOL == $t ) {

					if( is_bool( $arg ) ) {
						$args2[$argkey] = $arg;
					}

				}
				
				if( self::TYPE_INTEGER == $t ) {
					//try typecasting
					$arg = (int)$arg;

					if( is_int( $arg ) ) {
						$args2[$argkey] = $arg;
					}
				}


			}
		}

		return $args2;
	}


	function milk_type_check($type){
		$valid_milk_types = array('regular','non-fat','low-fat','soymilk');
		if(in_array( $type,$valid_milk_types )){

			return true;
		}
			
		array_push($this->errors,'Milk Type: '.$type.'<br/>- Neither our cows nor our soy plant gives this kind of milk :(');
		return false;
	}




	function coffee_type_check($type) {
		$valid_coffee_types = array('dark','blonde','medium');
		if(in_array( $type,$valid_coffee_types ))
			return true;
		    array_push($this->errors,'Coffee Type: '.$type.'<br/>- What kind of a coffee is that!');
		return false;
	}

	function surup_type_check($type) {
		$valid_coffee_types = array('none','vanilla','caramel','almond','hazelnut');
		if(in_array( $type,$valid_coffee_types )){
			return true;
		}
		array_push($this->errors,'surup Type: '.$type.'<br/>- Sorry! Just run out of that one! Really! Come next week, thanks!');
		return false;
	}


	function errorHandler($echo = true){
		if(count($this->errors) > 0) {
			if($echo){
				echo '<div style="font-weight:bold;">';
				echo '<h2 style="color:crimson">Sorry! But I got some errors!</h2>';
				foreach ($this->errors as $key => $error)
					echo '<p>'.$error."</p>";
				    echo '<div>';
			}	
			//yes it has errors, dont make the coffee
			return true;
		}

		return false;
	}

	function boil_water()
	
	{
		$initialwater_temp = $this->check_initial_water_temp();

		if($initialwater_temp < self::WATER_TEMPERATURE){
			while($initialwater_temp < self::WATER_TEMPERATURE){
				$initialwater_temp++;
			}
			return true;
		}
	}

	function check_initial_water_temp(){
		return rand(1,10);
	}

	function Make_The_Coffee(){

		//it has errors! stop!
		if($this->errorHandler(false)){
			return $this->errorHandler();
		}

		//get ingrediants
		$coffee_type = $this->coffee_type;

		$milk = $this->milk;

		$milk_type = $this->milk_type;

		$sugar = $this->sugar;

		$surup = $this->surup;
		

		//boil the water
		if( $this->boil_water() ) {
			
			$this->coffee .= '<h2>Your coffee is ready! It\'s a ';

			$this->coffee .= $coffee_type.' coffee ';

			if($milk){
				$this->coffee .= 'with '.$milk_type.' milk, ';
			}else{
				//no milk
				$this->coffee .= 'without milk, ';
			}

			if($sugar > 0){
				$this->coffee .= $sugar.' sugar added ';
			}else{
				$this->coffee .= 'no sugar added ';
			}

			if('none' == $surup){
				$this->coffee .= 'and without any surups. ';
			}else{
				$this->coffee .= 'and flavored with '.$surup.' surup. ';
			}

			$this->coffee .= 'Enjoy it!<h2>';

		}

		echo $this->coffee;

		return;
	}

}



$my_morning_coffee = new DionCoffeeMaker(array('surup' => 'almond'));
