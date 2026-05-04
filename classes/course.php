<?php

class Course
{
    public $id;
    public $instructorId;
    public $instructorName;
    public $title;
    public $description;
    public $shortDescription;
    public $price;
    public $category;
    public $level;
    public $duration;
    public $lessonsCount;
    public $thumbnail;
    public $promoVideo;
    public $curriculum;
    public $resources;
    public $status;
    public $featured;
    public $rating;
    public $enrollmentCount;
    public $createdAt;
    public $updatedAt;

    private static $storage;

    private static function getStorage()
    {
        if (!self::$storage) {
            self::$storage = new FileStorage('courses.json');
        }
        return self::$storage;
    }

    public function __construct($id, $instructorId, $instructorName, $title, $description, $shortDescription, $price, $category, $level, $duration, $lessonsCount, $thumbnail, $promoVideo, $curriculum = [], $resources = [], $status = 'draft', $featured = false, $rating = 0, $enrollmentCount = 0, $createdAt = null, $updatedAt = null)
    {
        $this->id              = $id;
        $this->instructorId    = $instructorId;
        $this->instructorName  = $instructorName;
        $this->title           = $title;
        $this->description     = $description;
        $this->shortDescription = $shortDescription;
        $this->price           = $price;
        $this->category        = $category;
        $this->level           = $level;
        $this->duration        = $duration;
        $this->lessonsCount    = $lessonsCount;
        $this->thumbnail       = $thumbnail;
        $this->promoVideo      = $promoVideo;
        $this->curriculum      = $curriculum;
        $this->resources       = $resources;
        $this->status          = $status;
        $this->featured        = $featured;
        $this->rating          = $rating;
        $this->enrollmentCount = $enrollmentCount;
        $this->createdAt       = $createdAt ?? date('Y-m-d H:i:s');
        $this->updatedAt       = $updatedAt ?? date('Y-m-d H:i:s');
    }

    public function toArray()
    {
        return [
            'id'               => $this->id,
            'instructor_id'    => $this->instructorId,
            'instructor_name'  => $this->instructorName,
            'title'            => $this->title,
            'description'      => $this->description,
            'short_description'=> $this->shortDescription,
            'price'            => $this->price,
            'category'         => $this->category,
            'level'            => $this->level,
            'duration'         => $this->duration,
            'lessons'          => $this->lessonsCount,
            'thumbnail'        => $this->thumbnail,
            'promo_video'      => $this->promoVideo,
            'curriculum'       => $this->curriculum,
            'resources'        => $this->resources,
            'status'           => $this->status,
            'featured'         => $this->featured,
            'rating'           => $this->rating,
            'enrollment_count' => $this->enrollmentCount,
            'created_at'       => $this->createdAt,
            'updated_at'       => $this->updatedAt,
        ];
    }

    public function save()
    {
        $courses = self::getStorage()->readAll();
        $found = false;
        foreach ($courses as &$c) {
            if ($c['id'] == $this->id) {
                $c = $this->toArray();
                $found = true;
                break;
            }
        }
        if (!$found) {
            $courses[] = $this->toArray();
        }
        return self::getStorage()->writeAll($courses);
    }

    // Static finders
    public static function findById($id)
    {
        $data = self::getStorage()->find('id', $id);
        if ($data) {
            return self::fromArray($data);
        }
        return null;
    }

    public static function getAll()
    {
        $all = self::getStorage()->readAll();
        $courses = [];
        foreach ($all as $data) {
            $courses[] = self::fromArray($data);
        }
        return $courses;
    }

    public static function getPublished()
    {
        $filtered = self::getStorage()->where(function ($c) {
            return $c['status'] === 'published';
        });
        $courses = [];
        foreach ($filtered as $data) {
            $courses[] = self::fromArray($data);
        }
        return $courses;
    }

    public static function getByInstructor($instructorId)
    {
        $filtered = self::getStorage()->where(function ($c) use ($instructorId) {
            return $c['instructor_id'] == $instructorId;
        });
        $courses = [];
        foreach ($filtered as $data) {
            $courses[] = self::fromArray($data);
        }
        return $courses;
    }

    public static function getFeatured($limit = 4)
    {
        $all = self::getStorage()->where(function ($c) {
            return $c['featured'] && $c['status'] === 'published';
        });
        usort($all, function ($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        $all = array_slice($all, 0, $limit);
        $courses = [];
        foreach ($all as $data) {
            $courses[] = self::fromArray($data);
        }
        return $courses;
    }

    public static function create($data)
    {
        $storage = self::getStorage();
        $id = $storage->nextId('id');
        $course = new self(
            $id,
            $data['instructor_id'] ?? 0,
            $data['instructor_name'] ?? '',
            $data['title'],
            $data['description'] ?? '',
            $data['short_description'] ?? '',
            $data['price'] ?? 0,
            $data['category'] ?? '',
            $data['level'] ?? 'beginner',
            $data['duration'] ?? 0,
            $data['lessons'] ?? 0,
            $data['thumbnail'] ?? '/assets/images/courses/default.jpg',
            $data['promo_video'] ?? '',
            $data['curriculum'] ?? [],
            $data['resources'] ?? [],
            $data['status'] ?? 'draft',
            $data['featured'] ?? false,
            $data['rating'] ?? 0
        );
        $course->save();
        return $course;
    }

    private static function fromArray($data)
    {
        return new self(
            $data['id'],
            $data['instructor_id'],
            $data['instructor_name'] ?? '',
            $data['title'],
            $data['description'],
            $data['short_description'],
            $data['price'],
            $data['category'],
            $data['level'],
            $data['duration'],
            $data['lessons'] ?? 0,
            $data['thumbnail'],
            $data['promo_video'],
            $data['curriculum'] ?? [],
            $data['resources'] ?? [],
            $data['status'],
            $data['featured'] ?? false,
            $data['rating'] ?? 0,
            $data['enrollment_count'] ?? 0,
            $data['created_at'],
            $data['updated_at']
        );
    }
}