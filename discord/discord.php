<?php
    class Discord {
        public function __construct($client_id, $client_secret, $redirect_uri, $scope) {
            $this->client_id = $client_id;
            $this->client_secret = $client_secret;
            $this->redirect_uri = $redirect_uri;
            $this->scope = $scope;
        }

        public function getAuthUrl() {
            return 'https://discordapp.com/api/oauth2/authorize?client_id=' . $this->client_id . '&redirect_uri=' . urlencode($this->redirect_uri) . '&response_type=code&scope=' . rawurlencode($this->scope);
        }

        public function getAccessToken($code) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://discordapp.com/api/oauth2/token');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
                'client_id' => $this->client_id,
                'client_secret' => $this->client_secret,
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => $this->redirect_uri,
                'scope' => rawurlencode($this->scope)
            ]));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
            return json_decode($response, true);
        }

        public function getUser($access_token) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://discordapp.com/api/users/@me');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Authorization: Bearer ' . $access_token,
                'Content-Type: application/x-www-form-urlencoded'
            ));
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
            return json_decode($response, true);
        }

        public function getGuilds($access_token) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://discordapp.com/api/users/@me/guilds');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Authorization: Bearer ' . $access_token
            ));
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
            return json_decode($response, true);
        }

        public function joinGuild($guild_id, $access_token, $bot_token, $user_id, $nick = null, $roles = []) {
            $payload = [
                'access_token' => $access_token,
                'nick' => $nick,
                'roles' => $roles,
            ];

            $header = ['Authorization: Bot ' . $bot_token, 'Content-Type: application/json'];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://discordapp.com/api/guilds/' . $guild_id . '/members/' . $user_id);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
            if (!$response) {
                return false;
            } else {
                return true;
            }
        }
    }