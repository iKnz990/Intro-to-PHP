<?php
class Booking {
    private $bookingId;
    private $userName;
    private $userEmail;
    private $bookingDate;
    private $bookingTime;
    private $price;
    public $services;


    public function __construct($bookingId, $userName, $userEmail, $bookingDate, $bookingTime, $price, $services = []) {
        $this->bookingId = $bookingId;
        $this->userName = $userName;
        $this->userEmail = $userEmail;
        $this->bookingDate = $bookingDate;
        $this->bookingTime = $bookingTime;
        $this->price = $price;
        $this->services = $services;

    }



    public function toJSON() {
        return json_encode([
            'bookingId' => $this->bookingId,
            'userName' => $this->userName,
            'userEmail' => $this->userEmail,
            'bookingDate' => $this->bookingDate,
            'bookingTime' => $this->bookingTime,
            'price' => $this->price,
            'services' => $this->services
        ]);
    }

    /**
     * Get the value of bookingId
     */
    public function getBookingId()
    {
        return $this->bookingId;
    }

    /**
     * Set the value of bookingId
     */
    public function setBookingId($bookingId): self
    {
        $this->bookingId = $bookingId;

        return $this;
    }

    /**
     * Get the value of userEmail
     */
    public function getUserEmail()
    {
        return $this->userEmail;
    }

    /**
     * Set the value of userEmail
     */
    public function setUserEmail($userEmail): self
    {
        $this->userEmail = $userEmail;

        return $this;
    }

    /**
     * Get the value of bookingDate
     */
    public function getBookingDate()
    {
        return $this->bookingDate;
    }

    /**
     * Set the value of bookingDate
     */
    public function setBookingDate($bookingDate): self
    {
        $this->bookingDate = $bookingDate;

        return $this;
    }

    /**
     * Get the value of price
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set the value of price
     */
    public function setPrice($price): self
    {
        $this->price = $price;

        return $this;
    }
}
