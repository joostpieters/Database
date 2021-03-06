<?php
namespace gb\mapper;

$EG_DISABLE_INCLUDES=true;
require_once( "gb/mapper/Mapper.php" );
require_once( "gb/domain/Customer.php" );

/* Class handling all queries about customers */
class CustomerMapper extends Mapper {

    function __construct() {
        parent::__construct();
        $this->selectStmt = "SELECT * FROM CUSTOMER where ssn = ?";
        $this->selectAllStmt = "SELECT * FROM CUSTOMER ";        
    } 
	
    /* Creates a collection with all customers from the database
	/* @return collection with all customers */
    function getCollection( array $raw ) {
        $customerCollection = array();
        foreach($raw as $row) {
            array_push($customerCollection, $this->doCreateObject($row));
        }
		
        return $customerCollection;
    }

	/* Create a new customer object with the given attributes 
	/* @return new customer object	*/
    protected function doCreateObject( array $array ) {
       
        $obj = null;  

		/* Check if there are attributes to be adjusted */
        if (count($array) > 0) {
            $obj = new \gb\domain\Customer( $array['ssn'] );

            $obj->setSsn($array['ssn']);
            $obj->setNumber($array["number"]);
            $obj->setFirstName($array['first_name']);
            $obj->setLastName($array['last_name']);
            $obj->setStreet($array['street']);
            $obj->setBus($array['bus']);
            $obj->setPostalCode($array['postal_code']);
            $obj->setCity($array['city']);
        } 
     
        return $obj;
    }

	/* Insert a given customer into the database and update it's attributes */
    protected function doInsert( \gb\domain\DomainObject $object ) {
			
			$con = $this->getConnectionManager();
			$query = 'INSERT INTO CUSTOMER (ssn, first_name, last_name, street, number, bus, postal_code, city) 
					  VALUES (:ssn,:first_name,:last_name,:street,:number,:bus,:postal_code,:city)';
			$con->executeUpdateStatement ($query, 
			array
			(
				'ssn' => $object->getSsn(),
				'first_name' => $object->getFirstName(),
				'last_name' => $object->getLastName(),
				'street' => $object->getStreet(),
				'number' => $object->getNumber(),
				'bus' => $object->getBus(),
				'postal_code' => $object->getPostalCode(),
				'city' => $object->getCity(),
			));
		
    }
    
	/* Not implemented: update the attributes of a given customer object */
    function update( \gb\domain\DomainObject $object ) {

    }
	
    function selectStmt() {
        return $this->selectStmt;
    }
	
    function selectAllStmt() {
        return $this->selectAllStmt;
    }
	
	/* Returns a collection with all the different cities from the customer in the database */
	function getCities() {
		$con = $this->getConnectionManager();
        $selectAllStmt = "SELECT DISTINCT city FROM CUSTOMER";
        $cities = $con->executeSelectStatement($selectAllStmt, array());  
		
		/* Make twodimensional array with all customers and their attributes (all empty except city) */
		for($i = 0; $i < sizeof($cities); $i++)
		{
			$initCities[$i] = 
			array
				(
					'ssn' => "",
					'first_name' => "",
					'last_name' => "",
					'street' => "",
					'number' => "",
					'bus' => "",
					'postal_code' => "",
					'city' => $cities[$i]['city'],
				);	
		}
		
		/* Return the array as a collection */
        return $this->getCollection($initCities);	
	}
	
	/* Returns a collection with all the customers living in the given city */
    function getCustomersInCity ($city) {
        $con = $this->getConnectionManager();
        $selectStmt = "SELECT * FROM CUSTOMER where city = ?";
        $cities = $con->executeSelectStatement($selectStmt, array($city));     
		
        return $this->getCollection($cities);
    }
	
}

?>
