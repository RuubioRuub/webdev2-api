<?php

namespace Repositories;

use PDO;
use PDOException;
use Repositories\Repository;

class GameRepository extends Repository
{
    function getAll($offset = NULL, $limit = NULL)
    {
        try {
            $query = "SELECT * FROM game";
            if (isset($limit) && isset($offset)) {
                $query .= " LIMIT :limit OFFSET :offset ";
            }
            $stmt = $this->connection->prepare($query);
            if (isset($limit) && isset($offset)) {
                $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            }
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\Game');
            $articles = $stmt->fetchAll();

            return $articles;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function getSelectedGame($id)
    {
        try {
            $stmt = $this->connection->prepare("SELECT * FROM game WHERE gameID = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\Game');
            $game = $stmt->fetch();

            return $game;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function insert($game)
    {
        try {
            $stmt = $this->connection->prepare("INSERT into game (title, publisher, genre, description) VALUES (:title, :publisher, :genre, :description)");
            $stmt->bindParam(":title", $game->title);
            $stmt->bindParam(":publisher", $game->publisher);
            $stmt->bindParam(":genre", $game->genre);
            $stmt->bindParam(":description", $game->description);

            $stmt->execute();

            $game->id = $this->connection->lastInsertId();

            return $game;
        } catch (PDOException $e) {
            echo $e;
        }
    }


    function update($game, $id)
    {
        try {
            $stmt = $this->connection->prepare("UPDATE game SET title = ?, publisher = ?, genre = ?, description = ? WHERE gameID = ?");

            $stmt->execute([$game->title, $game->publisher, $game->genre, $game->description, $id]);

            return $game;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function delete($id)
    {
        try {
            $stmt = $this->connection->prepare("DELETE FROM game WHERE gameID = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return;
        } catch (PDOException $e) {
            echo $e;
        }
    }
}
