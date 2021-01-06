<?php
  
	require_once('lib/nusoap.php');
	$error  = '';
	$result = array();
	$response = '';
    $wsdl = "http://localhost/webservices2-main/ws1_server.php?wsdl";
    
    if (isset($_GET["pays"]))
    {
        $name=$_GET["pays"];
        $client = new nusoap_client($wsdl, true);
			$err = $client->getError();
			if ($err) {
				echo '<h2>Constructor error</h2>' . $err;
				// At this point, you know the call that follows will fail
			    exit();
			}
			 try {
				 echo "test";
				$rs = $client->call('FullCountryInfo', array($name));
				//$rs = json_decode($rs);
                var_dump($rs);
			  }catch (Exception $e) {
			    echo 'Caught exception: ',  $e->getMessage(), "\n";
			 }
    }
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
				$result = $client->call('fetchPaystData', array($name));
				$result = json_decode($result);
				var_dump( $result);
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
  <title>COUNTRY_INFOS</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <h2>FIND_COUNTRY</h2>
  <p>Enter <strong>ID</strong> of country and click  <strong>Fetch country Information</strong> button.</p>
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
	      <label for="langue">ID:</label>
	      <input type="text" class="form-control" name="name" id="name" placeholder="Enter NAME" required>
	    </div>
	    <button type="submit" name='sub' class="btn btn-default">Fetch COUNTRY Information</button>
    </form>
   </div>
   <br />
   <h2>COUNTRY Information</h2>
  <table class="table">
    <thead>
      <tr>
        <th>Name</th>
        <th>langue</th>
        <th>Action</th>

      </tr>
    </thead>
    <tbody>
    <?php if($result){ ?>
      	
		      <tr>
		        <td><?php echo $result->name; ?></td>
		        <td><?php echo $result->langue; ?></td>
                <td><a href=<?php echo '"http://localhost/webservices2-main/ws1_client.php?pays='.$result->name.'">plus information </a>';?></td>

		      </tr>
      <?php 
  		}else{ ?>
  			<tr>
		        <td colspan='5'>Enter Name and click on Fetch Pays Information button</td>
		      </tr>
  		<?php } ?>
    </tbody>
  </table>
	<div class='row'>
	<h2>Add New Pays</h2>
	 <?php if(isset($response->status)) {

	  if($response->status == 200){ ?>
		<div class="alert alert-success fade in">
    			<a href="#" class="close" data-dismiss="alert">&times;</a>
    			<strong>Success!</strong>&nbsp; Pays Added succesfully. 
	        </div>
	  <?php }elseif(isset($response) && $response->status != 200) { ?>
			<div class="alert alert-danger fade in">
    			<a href="#" class="close" data-dismiss="alert">&times;</a>
    			<strong>Error!</strong>&nbsp; Cannot Add a Pays. Please try again. 
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
	    <button type="submit" name='addbtn' class="btn btn-default">Add New Pays</button>
    </form>
   </div>
</div>

</body>
</html>



