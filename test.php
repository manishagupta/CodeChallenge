<html>
<div style="overflow:none;scroll:none;">
	<form action="test_result.php" method="post">
        <fieldset>
            <legend><h3>User Login</h3></legend>
            <table>
				<tr>
                    <td>Email:</td>
                    <td>
                        <input type="text" name="email">
                    </td>
					<td>Password:</td>
                    <td>
                        <input type="text" name="password">
                    </td>
               
                <input type="hidden" name="url" value="http://localhost/CodeChallenge/Webservice/login">
            </table>
            <input type="submit" value="submit">
        </fieldset>
    </form>
	
    <form action="test_result.php" method="post">
        <fieldset>
            <legend><h3>logout</h3></legend>
            <table>
                <input type="hidden" name="url" value="http://localhost/CodeChallenge/Webservice/logout">
            </table>
            <input type="submit" value="submit">
        </fieldset>
    </form>
	
	<form action="test_result.php" method="post">
        <fieldset>
            <legend><h3>getAllStates</h3></legend>
            <table>               
                <input type="hidden" name="url" value="http://localhost/CodeChallenge/Webservice/getAllStates">
            </table>
            <input type="submit" value="submit">
        </fieldset>
    </form>
	
	<form action="test_result.php" method="post">
        <fieldset>
            <legend><h3>PostState</h3></legend>
            <table>
				<tr>
                    <td>country_id:</td>
                    <td>
                        <input type="text" name="country_id">
                    </td>
					
               <td>name:</td>
                    <td>
                        <input type="text" name="name">
                    </td>
				<td>alias:</td>
                    <td>
                        <input type="text" name="alias">
                    </td>
				<td>state_code:</td>
                    <td>
                        <input type="text" name="state_code">
                    </td>
					
               
                <input type="hidden" name="url" value="http://localhost/CodeChallenge/Webservice/postState">
            </table>
            <input type="submit" value="submit">
        </fieldset>
    </form>
	
	<form action="test_result.php" method="post">
        <fieldset>
            <legend><h3>getAllDistricts</h3></legend>
            <table>
				<tr>
                    <td>State Id:</td>
                    <td>
                        <input type="text" name="state_id">
                    </td>
					
               
                <input type="hidden" name="url" value="http://localhost/CodeChallenge/Webservice/getAllDistricts">
            </table>
            <input type="submit" value="submit">
        </fieldset>
    </form>
	
	<form action="test_result.php" method="post">
        <fieldset>
            <legend><h3>postDistrict</h3></legend>
            <table>
				<tr>
				<td>country_id:</td>
                    <td>
                        <input type="text" name="country_id">
                    </td>
					
				<td>state_id:</td>
                    <td>
                        <input type="text" name="state_id">
                    </td>
					
               <td>name:</td>
                    <td>
                        <input type="text" name="name">
                    </td>
				<td>alias:</td>
                    <td>
                        <input type="text" name="alias">
                    </td>
				<td>state_code:</td>
                    <td>
                        <input type="text" name="state_code">
                    </td>
					
               
                <input type="hidden" name="url" value="http://localhost/CodeChallenge/Webservice/postDistrict">
            </table>
            <input type="submit" value="submit">
        </fieldset>
    </form>
	<form action="test_result.php" method="post">
        <fieldset>
            <legend><h3>getChild</h3></legend>
            <table>
				<tr>
                    <td>id:</td>
                    <td>
                        <input type="text" name="id">
                    </td>
					
               
                <input type="hidden" name="url" value="http://localhost/CodeChallenge/Webservice/getChild">
            </table>
            <input type="submit" value="submit">
        </fieldset>
    </form>
	
	<form action="test_result.php" method="post">
        <fieldset>
            <legend><h3>postChild</h3></legend>
            <table>
				<tr>
                    <td>username:</td>
                    <td>
                        <input type="text" name="username">
                    </td>
					
               <td>email:</td>
                    <td>
                        <input type="text" name="email">
                    </td>
				<td>password:</td>
                    <td>
                        <input type="text" name="password">
                    </td>
				<td>first_name:</td>
                    <td>
                        <input type="text" name="first_name">
                    </td>
					<td>gender(M,F):</td>
                    <td>
                        <input type="text" name="gender">
                    </td>
					
               
                <input type="hidden" name="url" value="http://localhost/CodeChallenge/Webservice/postState">
            </table>
            <input type="submit" value="submit">
        </fieldset>
    </form>
	