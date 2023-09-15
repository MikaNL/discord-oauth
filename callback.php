<?php
    include('config.php');
    require_once('discord/discord.php');
    $discord = new Discord($client_id, $client_secret, $redirect_uri, $scope);

    session_start();
    if (!empty($_GET['code'] && empty($_SESSION['user']))) {
        $token = $discord->getAccessToken($_GET['code']);
        $user = $discord->getUser($token['access_token']);
        $_SESSION['user'] = $user;
    }
    if (empty($_SESSION['user'])) {
        header('Location: index.php');
        die();
    }

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Discord OAuth2 Example</title>
        <link href="./assets/css/style.css" rel="stylesheet">
    </head>
    <body>
        <div class="flex items-center justify-center h-screen bg-discord flex-col">
            <div class="text-white text-3xl">Welcome to the dashboard, </div>
            <div class="flex items-center mt-4">
                <img class="rounded-full w-12 h-12 mr-3" src="https://cdn.discordapp.com/avatars/<?php echo $_SESSION['user']['id']; ?>/<?php echo $_SESSION['user']['avatar']; ?>.png" alt="<?php $_SESSION['user']['username'] ?>'s pfp">
                <span class="text-3xl text-white font-semibold"><?php echo $_SESSION['user']['username']; ?></span>
            </div>
            <a href="./logout.php" class="mt-5 text-white opacity-50 hover:opacity-100 transition-all">Logout</a>
            </div>
        </div>
    </body>
</html>