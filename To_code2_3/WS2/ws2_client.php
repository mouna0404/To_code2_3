<?php
  
	require_once('lib/nusoap.php');
	$error  = '';
	$result = array();
	$response = '';
	$wsdl = "http://webservices.oorsprong.org/websamples.countryinfo/CountryInfoService.wso?WSDL";
	if(isset($_POST['sub'])){
		$name = trim($_POST['name']);
		if(!$name){
			$error = 'Name cannot be left blank.';
		}

		if(!$error){
			//create client object
			$client = new nusoap_client($wsdl, true);
			$err = $client->getError();
			if ($err) {
				echo '<h2>Constructor error</h2>' . $err;
				// At this point, you know the call that follows will fail
			    exit();
			}
			 try {
				 echo "test";
                $result = $client->call('FullCountryInfo', array("sCountryISOCode"=>$name));
	
			  }catch (Exception $e) {
			    echo 'Caught exception: ',  $e->getMessage(), "\n";
			 }
		}
	}	

	/* Add new book **/
	if(isset($_POST['addbtn'])){
		$name = trim($_POST['name']);
		$langue = trim($_POST['langue']);


		//Perform all required validations here
		if(!$langue || !$name){
			$error = 'All fields are required.';
		}

		if(!$error){
			//create client object
			$client = new nusoap_client($wsdl, true);
			$err = $client->getError();
			if ($err) {
				echo '<h2>Constructor error</h2>' . $err;
				// At this point, you know the call that follows will fail
			    exit();
			}
			 try {
				/** Call insert book method */
                 $response =  $client->call('insertPays', array($name, $langue));
        		 $response = json_decode($response);
				 var_dump($response);
			  }catch (Exception $e) {
			    echo 'Caught exception: ',  $e->getMessage(), "\n";
			 }
		}
	}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Book Store Web Service</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <h2>Books Store SOAP Web Service</h2>
  <p>Enter <strong>ISBN</strong> of book and click <strong>Fetch Book Information</strong> button.</p>
  <br />       
  <div class='row'>
  	<form class="form-inline" method = 'post' name='form1'>
  		<?php if($error) { ?> 
	    	<div class="alert alert-danger fade in">
    			<a href="#" class="close" data-dismiss="alert">&times;</a>
    			<strong>Error!</strong>&nbsp;<?php echo $error; ?> 
	        </div>
		<?php } ?>
	    <div class="form-group">
	      <label for="langue">ISBN:</label>
	      <input type="text" class="form-control" name="name" id="name" placeholder="Enter NAME" required>
	    </div>
	    <button type="submit" name='sub' class="btn btn-default">Fetch Etudiant Information</button>
    </form>
   </div>
   <br />
   <h2>Etudiant Information</h2>
  <table class="table">
    <thead>
      <tr>
        <th>Capital</th>
        <th>ISO CODE</th>

      </tr>
    </thead>
    <tbody>
    <?php if($result){ ?>
      	
		      <tr>
		        <td><?php echo $result['FullCountryInfoResult']['sCapitalCity']; ?></td>
		        <td><?php echo $result['FullCountryInfoResult']['sCurrencyISOCode']; ?></td>

		      </tr>
      <?php 
  		}else{ ?>
  			<tr>
		        <td colspan='5'>Enter Name and click on Fetch Etudiant Information button</td>
		      </tr>
  		<?php } ?>
    </tbody>
  </table>
	<div class='row'>
	<h2>Add New Etudiant</h2>
	 <?php if(isset($response->status)) {

	  if($response->status == 200){ ?>
		<div class="alert alert-success fade in">
    			<a href="#" class="close" data-dismiss="alert">&times;</a>
    			<strong>Success!</strong>&nbsp; Etudiant Added succesfully. 
	        </div>
	  <?php }elseif(isset($response) && $response->status != 200) { ?>
			<div class="alert alert-danger fade in">
    			<a href="#" class="close" data-dismiss="alert">&times;</a>
    			<strong>Error!</strong>&nbsp; Cannot Add a Etudiant. Please try again. 
	        </div>
	 <?php } 
	 }
	 ?>
  	<form class="form-inline" method = 'post' name='form1'>
  		<?php if($error) { ?> 
	    	<div class="alert alert-danger fade in">
    			<a href="#" class="close" data-dismiss="alert">&times;</a>
    			<strong>Error!</strong>&nbsp;<?php echo $error; ?> 
	        </div>
		<?php } ?>
	    <div class="form-group">
	      <label for="langue"></label>
	      <input type="text" class="form-control" name="langue" id="langue" placeholder="Enter langue" required>
				<input type="text" class="form-control" name="name" id="name" placeholder="Enter Name" required>
			    </div>
	    <button type="submit" name='addbtn' class="btn btn-default">Add New Etudiant</button>
    </form>
   </div>
</div>

</body>
</html>



