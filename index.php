<?php
    include('config.php');
    require_once('discord/discord.php');
    $discord = new Discord($client_id, $client_secret, $redirect_uri, $scope);
    session_start();
    if (!empty($_SESSION['user'])) {
        header('Location: callback.php');
        die();
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Discord OAuth2 Example</title>
        <link href="./assets/css/style.css" rel="stylesheet">
    </head>
    <body class="h-screen w-screen flex justify-center items-center bg-discord">
        <a class="bg-[#5865F2] px-8 py-4 rounded-lg text-white font-semibold text-lg gap-8" href="<?php echo $discord->getAuthUrl(); ?>">
            <img src="./assets/img/discord-mark-white.svg" alt="" class="w-8 h-8 inline-block mr-2">
            Login with Discord
        </a>
    </body>
</html>