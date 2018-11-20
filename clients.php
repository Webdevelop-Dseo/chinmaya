<?php
include('config.php');
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Sample form</title>
    </head>
    <body>
<?php
//We check if the form has been sent
if(isset($_POST['name'], $_POST['address'], $_POST['city'],$_POST['pincode'], $_POST['email'], $_POST['mobile_num']) and $_POST['name']!='')
{
	//We remove slashes depending on the configuration
	if(get_magic_quotes_gpc())
	{
		$_POST['name'] = stripslashes($_POST['name']);
		$_POST['address'] = stripslashes($_POST['address']);
		$_POST['city'] = stripslashes($_POST['city']);
		$_POST['pincode'] = stripslashes($_POST['pincode']);
		$_POST['email'] = stripslashes($_POST['email']);
		$_POST['mobile_num'] = stripslashes($_POST['mobile_num']);
	}
//We check if the mobile number has 10 or more numbers
		if(strlen($_POST['mobile_num'])>=10)
		{
			//We check if the email form is valid
			if(preg_match('#^(([a-z0-9!\#$%&\\\'*+/=?^_`{|}~-]+\.?)*[a-z0-9!\#$%&\\\'*+/=?^_`{|}~-]+)@(([a-z0-9-_]+\.?)*[a-z0-9-_]+)\.[a-z]{2,}$#i',$_POST['email']))
			{
				//We protect the variables
				$name = mysql_real_escape_string($_POST['name']);
				$address = mysql_real_escape_string($_POST['address']);
				$city = mysql_real_escape_string($_POST['city']);
				$pincode = mysql_real_escape_string($_POST['pincode']);
				$email = mysql_real_escape_string($_POST['email']);
				$mobile_num = mysql_real_escape_string($_POST['mobile_num']);
				//We check if there is no other user using the same name
				$dn = mysql_num_rows(mysql_query('select id from clients where name="'.$name.'"'));
				if($dn==0)
				{
					//We count the number of clients to give an ID to this one
					$dn2 = mysql_num_rows(mysql_query('select id from clients'));
					$id = $dn2+1;
					//We save the informations to the databse
					if(mysql_query('insert into clients(id,name,address,city,pincode,email,mobile_num) values ('.$id.', "'.$name.'", "'.$address.'", "'.$city.'",  "'.$pincode.'", "'.$email.'", "'.$mobile_num.'")'))
					{
						//We dont display the form
						$form = false;
?>
<div class="message">Your data successfully inserted <br />
<?php
					}
					else
					{
						//Otherwise, we say that an error occured
						$form = true;
						$message = 'An error occurred while submiting form.';
					}
				}
				else
				{
					//Otherwise, we say the name is not available
					$form = true;
					$message = 'This Name already existing in List';
				}
			}
			else
			{
				//Otherwise, we say the email is not valid
				$form = true;
				$message = 'The email you entered is not valid.';
			}
		}
		else
		{
			//Otherwise, we say the Mobile is not valid
			$form = true;
			$message = 'Mobile number must contain at least 10 numbers.';
		}
}
else
{
	$form = true;
}	
if($form)
{
	//We display a message if necessary
	if(isset($message))
	{
		echo '<div class="message">'.$message.'</div>';
	}
	//We display the form
?>
<div class="content">
    <form action="clients.php" method="post">
       Sample info testing <br/>
        <div class="center">
            <label for="name">Name</label> <input type="text" name="name" value="<?php if(isset($_POST['name'])){echo htmlentities($_POST['name'], ENT_QUOTES, 'UTF-8');} ?>" /><br />
            <label for="address">Address</label><input type="text" name="address" value="<?php if(isset($_POST['address'])){echo htmlentities($_POST['address'], ENT_QUOTES, 'UTF-8');} ?>" /><br />
			<label for="city">City</label><input type="text" name="city" name="city" value="<?php if(isset($_POST['city'])){echo htmlentities($_POST['city'], ENT_QUOTES, 'UTF-8');} ?>" /><br />
            <label for="pincode">Pincode</label><input type="text" name="pincode" value="<?php if(isset($_POST['pincode'])){echo htmlentities($_POST['pincode'], ENT_QUOTES, 'UTF-8');} ?>" /><br />
			<label for="email">Email</label><input type="text" name="email" value="<?php if(isset($_POST['email'])){echo htmlentities($_POST['email'], ENT_QUOTES, 'UTF-8');} ?>" /><br />
			<label for="mobile_num">Mobile number</label><input type="text" name="mobile_num" value="<?php if(isset($_POST['mobile_num'])){echo htmlentities($_POST['mobile_num'], ENT_QUOTES, 'UTF-8');} ?>" /><br />
            <input type="submit" value="Submit"/>
		</div>
    </form>
</div>
<?php
}
?>
	</body>
</html>