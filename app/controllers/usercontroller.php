<?php

namespace Controllers;

use Exception;
use Services\UserService;
use \Firebase\JWT\JWT;
use PDOException;

class UserController extends Controller
{
    private $service;

    // initialize services
    function __construct()
    {
        $this->service = new UserService();
    }

    public function login()
    {
        try {
            // read user data from request body
            $postedUser = $this->createObjectFromPostedJson("Models\\User");

            // get user from db
            $user = $this->service->checkUsernamePassword($postedUser->username, $postedUser->password);

            // if the method returned false or no user, the username and/or password were incorrect
            if (!$user || !$user->username) {
                $this->respondWithError(401, "Invalid login");
                return;
            }

            // generate jwt
            $tokenResponse = $this->generateJwt($user);

            $this->respond($tokenResponse);
        } catch (PDOException $e) {
            $this->respondWithError(500, $e);
        }
    }

    public function generateJwt($user)
    {
        $secret_key = "MY_SECRET_KEY";

        $issuer = "HS_FRONT"; // this can be the domain/servername that issues the token
        $audience = "HS_BACK"; // this can be the domain/servername that checks the token

        $issuedAt = time(); // issued at
        $notbefore = $issuedAt; //not valid before 
        $expire = $issuedAt + 600; // expiration time is set at +600 seconds (10 minutes)

        // JWT expiration times should be kept short (10-30 minutes)
        // A refresh token system should be implemented if we want clients to stay logged in for longer periods

        $payload = array(
            "iss" => $issuer,
            "aud" => $audience,
            "iat" => $issuedAt,
            "nbf" => $notbefore,
            "exp" => $expire,
            "data" => array(
                "id" => $user->id,
                "username" => $user->username,
                "email" => $user->email,
                "role" => $user->role
            )
        );

        $jwt = JWT::encode($payload, $secret_key, 'HS256');

        return
            array(
                "message" => "Successful login.",
                "jwt" => $jwt,
                "username" => $user->username,
                "role" => $user->role,
                "expireAt" => $expire
            );
    }

    public function register()
    {
        try {
            $postedUser = $this->createObjectFromPostedJson("Models\\User");

            $checkIfUserExists = $this->service->checkEmailAndUsername($postedUser->email, $postedUser->username);

            if ($checkIfUserExists) {
                $message = '';
                if ($checkIfUserExists->username == $postedUser->username) {
                    $message = 'That username is already taken';
                } else {
                    $message = 'A user with that email adress already exists';
                }

                $this->respondWithError(409, $message);
                return;
            }

            $postedUser->password = password_hash($postedUser->password, PASSWORD_DEFAULT);

            $newUser = $this->service->register($postedUser);
            $newUser->password =

                $this->respond($newUser);
        } catch (PDOException $e) {
            $this->respondWithError(500, $e);
        }
    }

    public function getAll()
    {
        try {
            if (!$this->checkIfUserIsAdmin()) {
                return;
            }

            $offset = NULL;
            $limit = NULL;

            if (isset($_GET["offset"]) && is_numeric($_GET["offset"])) {
                $offset = $_GET["offset"];
            }
            if (isset($_GET["limit"]) && is_numeric($_GET["limit"])) {
                $limit = $_GET["limit"];
            }

            $users = $this->service->getAll($offset, $limit);

            $this->respond($users);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }
}
