<?php
class Article
{
    private $id;
    private $name;
    private $text;
    private $publicationDate;
    private $contributorName;

    // constructor
    public function __construct($name, $text, $publicationDate, $contributorName, $id = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->text = $text;
        $this->publicationDate = $publicationDate;
        $this->contributorName = $contributorName;
    }

    // getters
    public function getID()
    {
        return $this->id;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getText()
    {
        return $this->text;
    }
    public function getPublicationDate()
    {
        return $this->publicationDate;
    }
    public function getContributorName()
    {
        return $this->contributorName;
    }
    // setters
    public function setName($name)
    {
        $this->name = $name;
    }
    public function setText($text)
    {
        $this->text = $text;
    }
    public function setPublicationDate($publicationDate)
    {
        $this->publicationDate = $publicationDate;
    }
    public function setContributorName($contributorName)
    {
        $this->contributorName = $contributorName;
    }

}
?>