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
        $offset = NULL;
        $limit = NULL;

        if (isset($_GET["offset"]) && is_numeric($_GET["offset"])) {
            $offset = $_GET["offset"];
        }
        if (isset($_GET["limit"]) && is_numeric($_GET["limit"])) {
            $limit = $_GET["limit"];
        }

        $games = $this->service->getAll($offset, $limit);

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
            if(!$this->checkIfUserIsAdmin()) {
                return;
            }

            $game = $this->createObjectFromPostedJson("Models\\Game");
            $this->service->insert($game);
            $this->respond($game);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }       
    }

    public function update($id)
    {
        try {
            if(!$this->checkIfUserIsAdmin()) {
                return;
            }

            $game = $this->createObjectFromPostedJson("Models\\Game");
            $this->service->update($game, $id);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respond($game);
    }

    public function delete($id)
    {
        try {
            if(!$this->checkIfUserIsAdmin()) {
                return;
            }
            
            $this->service->delete($id);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respond(true);
    }
}
