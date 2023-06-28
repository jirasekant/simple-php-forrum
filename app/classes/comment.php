<?php
class Comment
{
    private $contributorName;
    private $email;
    private $text;
    private $publicationDate;
    private $parentId;
    private $id;
    private $articleId;

    //constructor
    public function __construct($contributorName, $email, $text, $publicationDate, $articleId, $parentId = null, $id = null)
    {
        $this->contributorName = $contributorName;
        $this->email = $email;
        $this->text = $text;
        $this->publicationDate = $publicationDate;
        $this->parentId = $parentId;
        $this->articleId = $articleId;
        $this->id = $id;
    }

    // getters
    public function getContributorName()
    {
        return $this->contributorName;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function getText()
    {
        return $this->text;
    }
    public function getPublicationDate()
    {
        return $this->publicationDate;
    }
    public function getParentId()
    {
        return $this->parentId;
    }
    public function getId()
    {
        return $this->id;
    }
    public function getArticleId()
    {
        return $this->articleId;
    }

    // setters
    public function setContributorName($contributorName)
    {
        $this->contributorName = $contributorName;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }
    public function setText($text)
    {
        $this->text = $text;
    }
    public function setPublicationDate($publicationDate)
    {
        $this->publicationDate = $publicationDate;
    }
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;
    }
}
?>