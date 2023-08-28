<?php

namespace Controllers;

use Exception;
use Services\GameService;

class GameController extends Controller
{
    private $service;

    function __construct()
    {
        $this->service = new GameService();
    }

    public function getAll()
    {
        // Checks for a valid jwt, returns 401 if none is found
        // $token = $this->checkForJwt();
        // if (!$token)
        //     return;

        

        $games = $this->service->getAll();

        $this->respond($games);
    }

    public function getSelectedGame($id)
    {
        $game = $this->service->getSelectedGame($id);

        if (!$game) {
            $this->respondWithError(404, "game not found");
            return;
        }

        $this->respond($game);
    }

    public function create()
    {
        try {
            $game = $this->createObjectFromPostedJson("Models\\Game");
            $this->service->insert($game);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respond($game);
    }

    public function update($id)
    {
        try {
            $category = $this->createObjectFromPostedJson("Models\\Category");
            $this->service->update($category, $id);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respond($category);
    }

    public function delete($id)
    {
        try {
            $this->service->delete($id);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respond(true);
    }
}
