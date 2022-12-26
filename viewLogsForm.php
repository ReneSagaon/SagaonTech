<!DOCTYPE html>
<html>

    <head>
    <script type = "text/javascript">
        function displayLogs(){
            alert("Si llego");
        }
    </script>
    </head>

    <body>
        <form action="viewLogs.php" method="post">
            <label for="uname"><b>Username</b></label>
            <input type="text" placeholder="Enter Username" name="name" required>
            
            <label for="psw"><b>Password</b></label>
            <input type="password" placeholder="Enter Password" name="password" required>
            
            <button type="submit">Login</button>
        </form>
    </body>
</html>