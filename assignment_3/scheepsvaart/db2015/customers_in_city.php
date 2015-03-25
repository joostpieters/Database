<?php
	
	// Dit is de titel die op de pagina en in de menubalk
	// zal verschijnen.
	$title = "Customers in city";

	// Voer de inhoud van "top.inc.php" uit. Deze verzorgt de
	// algemene pagina lay-out en het menu.
	require("template/top.tpl.php");

    require_once( "gb/controller/ListCustomerInCityController.php" );
    $filterController = new gb\controller\ListCustomerInCityController();
    $filterController->process();
?>
<form method="post">
    
<?php
    require_once( "gb/mapper/CustomerMapper.php" );    
    $custMapper = new gb\mapper\CustomerMapper();//
 ?>

 <?php
	include('configuration.php');
	$PDO = new \PDO( $config["dsn"], $config["username"], $config["password"] );
	$stmt = $PDO->prepare("SELECT DISTINCT CITY FROM CUSTOMER");
	$stmt->execute(array());
	$allCustomer = $stmt->fetchAll( \PDO::FETCH_ASSOC);
?>
	
<table style="width: 100%">
    <tr>
        <td style="width: 10%"></td>
        <td style="width: 10%">City</td>
        <td style="width: 40%">
            <select style="width: 100%" id="selected_city">
				  <?php
				  
				  $index = 0;
					foreach ($allCustomer as $customer  ){
						$index++;
					?>
					<option value=<?php $customer['CITY'] ?>><?php echo $customer['CITY']; ?></option>
					
					<?php
					} ?>
            </select>
        </td>
        <td style="width: 10%"><input type="submit" value="List customers in the city" name="list_customer"></td>
        <td style="width: 30%"></td>
    </tr>
</table>    

<?php 
		$custMapper->getCustomersInCity(list_customer);
?>


//<?php echo $index; ?>
	<table>
            <tr>
                <td>Ssn</td>
                <td>First name</td>
                <td>Last name</td>
                <td>Address</td>
                <td>City</td>
            </tr>

      
<?php
    foreach($allCustomer as $customer) {
 ?>
       <tr>
		<td><?php echo $customer->getSsn(); ?></td>
		<td><?php echo $customer->getFirstName(); ?></td>
		<td><?php echo $customer->getLastName(); ?></td>
                <td><?php echo $customer->getAddress(); ?></td>
                <td><?php echo $customer->getCity(); ?></td>
	</tr>     
<?php        
}
?>
</table>
    
</form>    
<?php
	require("template/bottom.tpl.php");
?>