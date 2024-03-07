<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <input type="text" id="pass" oninput="showPassword()">
    <label for="pass" id="passwordLabel"></label>

    <script>
        function showPassword() {
            var input = document.getElementById("pass");
            var passwordLabel = document.getElementById("passwordLabel");

            // Obtén el valor del campo de entrada
            var password = input.value;

            // Aplica la función password_hash en JavaScript
            var hashedPassword = btoa(password);

            // Actualiza el contenido del label
            passwordLabel.textContent = hashedPassword;
        }
    </script>
</body>
</html>
