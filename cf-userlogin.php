<?php
	require('scripts/con_game.php'); // Ensure this file contains $connection

	header('Content-type: text/xml');

	// Sanitize POST data
	$username = mysqli_real_escape_string($connection, stripslashes($_POST['unm']));
	$password = strtoupper(substr(hash('sha512', $_POST['pwd'] . strtolower($_POST['unm'])), strlen($_POST['unm']), 17));
	$restrictionCode = mysqli_real_escape_string($connection, stripslashes($_POST['rtc']));

	// Check if the username is provided
	if (!empty($username)) {
		// Query to check if the username exists
		$sql = mysqli_query($connection, "SELECT * FROM meh_users WHERE Username = '$username'") or die("status=Error&strReason=" . mysqli_error($connection));
		
		if (mysqli_num_rows($sql) > 0) {
			// Query to check if username and password match
			$sql2 = mysqli_query($connection, "SELECT * FROM meh_users WHERE Username = '$username' AND Password = '$password'") or die("status=Error&strReason=" . mysqli_error($connection));
			
			if (mysqli_num_rows($sql2) > 0) {
				$CHAR = mysqli_fetch_assoc($sql2);
				if ($CHAR['ActivationFlag'] > 0) {
					$UPG_DATE = preg_replace('/\s+/', 'T', $CHAR['UpgradeExpire']); 
					$UPG = ($CHAR['UpgradeDays'] >= 0) ? 1 : 0;
					
					echo '<login bSuccess="1" userid="' . $CHAR['id'] . '" iAccess="' . $CHAR['Access'] . '" iUpg="16" iAge="' . $CHAR['Age'] . '" sToken="' . $CHAR['Password'] . '" dUpgExp="' . $UPG_DATE . '" iUpgDays="' . $CHAR['UpgradeDays'] . '" strEmail="' . $CHAR['Email'] . '" bCCOnly="0" dCreated="' . $CHAR['DateCreated'] . '" strCountryCode="US">';
					
					// Query to get server information
					$sql3 = mysqli_query($connection, "SELECT * FROM meh_servers") or die("status=Error&strReason=" . mysqli_error($connection));
					
					if (mysqli_num_rows($sql3) > 1) {
						while ($SERVER = mysqli_fetch_assoc($sql3)) {
							echo '<servers sName="' . $SERVER['Name'] . '" sPort="' . $SERVER['Port'] . '" sIP="' . $SERVER['IP'] . '"  iCount="' . $SERVER['Count'] . '" iMax="' . $SERVER['Max'] . '" bOnline="' . $SERVER['Online'] . '" iChat="' . $SERVER['Chat'] . '" bUpg="' . $SERVER['Upgrade'] . '" sLang="' . $SERVER['sLang'] . '"/>';
						}
					} else {
						$SERVER = mysqli_fetch_assoc($sql3);
						echo '<servers sName="' . $SERVER['Name'] . '" sPort="' . $SERVER['Port'] . '" sIP="' . $SERVER['IP'] . '" iCount="' . $SERVER['Count'] . '" iMax="' . $SERVER['Max'] . '" bOnline="' . $SERVER['Online'] . '" iChat="' . $SERVER['Chat'] . '" bUpg="' . $SERVER['Upgrade'] . '" sLang="' . $SERVER['sLang'] . '"/>';
						echo '<servers sName="' . $SERVER['Name'] . '" sPort="' . $SERVER['Port'] . '" sIP="' . $SERVER['IP'] . '" iCount="' . $SERVER['Count'] . '" iMax="' . $SERVER['Max'] . '" bOnline="' . $SERVER['Online'] . '" iChat="' . $SERVER['Chat'] . '" bUpg="' . $SERVER['Upgrade'] . '" sLang="' . $SERVER['sLang'] . '"/>';
					}
					
					echo "</login>";
				} else {
					echo '<login bSuccess="0" sMsg="You have been banned. If this is a mistake, please contact the support staff immediately."/>';
				}
			} else {
				echo '<login bSuccess="0" sMsg="The username and password you entered did not match. Please check the spelling and try again. OR Try pressing Ctrl+A and typing your information again"/>';
			}
		} else {
			echo '<login bSuccess="0" sMsg="The username you typed was not found. Please check the spelling and try again."/>';
		}
	} else {
		die('<login bSuccess="0" sMsg="Invalid Input"/>');
	}
?>
