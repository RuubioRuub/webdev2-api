<?php 
namespace Models;

class Review {
    public int $reviewID;
    public int $gameID;
    public string $title;
    public int $score;
    public string $body;
    public int $criticreview;
    public string $writer;
    public string $company;
}