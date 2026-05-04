<?php

class Testimonial
{
    public $id;
    public $userId;
    public $name;
    public $role;
    public $avatar;
    public $text;
    public $rating;
    public $status;
    public $createdAt;

    private static $storage;

    private static function getStorage()
    {
        if (!self::$storage) {
            self::$storage = new FileStorage('testimonials.json');
        }
        return self::$storage;
    }

    // Constructor, toArray, save, fromArray, getAll, getApproved, submit, approve, reject
    // (I'll keep it short; same pattern as above)
}