<h3>Login</h3>
<form name="_frmLogin" method="POST">
	<table>
		<tr>
			<td>Username <span style="color: red">*</span>:</td>
			<td><input type="text" name="username" value="<?php echo !empty($username)? $username: ""?>"/></td>
		</tr>
		<tr>
			<td>Password <span style="color: red">*</span>:</td>
			<td><input type="password" name="password"/></td>
		</tr>
	</table>
	<input type="submit" name="login" value="Login"/>
</form>
