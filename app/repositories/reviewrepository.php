<?php

namespace Repositories;

use Models\Review;
use PDO;
use PDOException;
use Repositories\Repository;

class ReviewRepository extends Repository
{
    function getAll($offset = NULL, $limit = NULL)
    {
        try {
            $query = "SELECT * FROM review ";
            if (isset($limit) && isset($offset)) {
                $query .= " LIMIT :limit OFFSET :offset ";
            }
            $stmt = $this->connection->prepare($query);
            if (isset($limit) && isset($offset)) {
                $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            }
            $stmt->execute();

            $reviews = array();
            while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
                $reviews[] = $this->rowToReview($row);
            }

            return $reviews;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function getReviewsForSelectedGame($gameid)
    {
        $stmt = $this->connection->prepare("SELECT * FROM review WHERE gameID = :id ORDER BY reviewID DESC ");
        $stmt->bindParam(':id', $gameid);
        $stmt->execute();

        $reviews = array();
        while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
            $reviews[] = $this->rowToReview($row);
        }

        return $reviews;
    }

    function getOne($id)
    {
        try {
            $query = "SELECT * FROM review WHERE reviewID = :id";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $row = $stmt->fetch();
            $review = $this->rowToReview($row);

            return $review;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function rowToReview($row)
    {
        $review = new Review();
        $review->reviewID = $row['reviewID'];
        $review->gameID = $row['gameID'];
        $review->title = $row['title'];
        $review->score = $row['score'];
        $review->body = $row['body'];
        $review->criticreview = $row['criticreview'];
        $review->writer = $row['writer'];
        $review->company = $row['company'];

        return $review;
    }

    function insert($review)
    {
        try {
            $stmt = $this->connection->prepare("INSERT into review (criticreview, gameID, writer, company, body, score, title) VALUES (?,?,?,?,?,?,?)");

            $stmt->execute([$review->criticreview, $review->gameID, $review->writer, $review->company, $review->body, $review->score, $review->title]);

            $review->reviewID = $this->connection->lastInsertId();

            return $this->getOne($review->reviewID);
        } catch (PDOException $e) {
            echo $e;
        }
    }


    function update($product, $id)
    {
        try {
            $stmt = $this->connection->prepare("UPDATE product SET name = ?, price = ?, description = ?, image = ?, category_id = ? WHERE id = ?");

            $stmt->execute([$product->name, $product->price, $product->description, $product->image, $product->category_id, $id]);

            return $this->getOne($product->id);
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function delete($id)
    {
        try {
            $stmt = $this->connection->prepare("DELETE FROM product WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return;
        } catch (PDOException $e) {
            echo $e;
        }
        return true;
    }
}
