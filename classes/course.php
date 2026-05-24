<?php

require_once __DIR__ . '/../database/db.php';

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
    public $curriculum = [];
    public $resources = [];
    public $status;
    public $featured;
    public $rating;
    public $enrollmentCount;
    public $createdAt;
    public $updatedAt;

    public function __construct($id = null)
    {
        if ($id !== null) {
            $this->load($id);
        }
    }

    private function load($id)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM courses WHERE course_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $this->hydrate($row);
            return true;
        }
        return false;
    }

    private function hydrate(array $data)
    {
        $this->id              = $data['course_id'] ?? null;
        $this->instructorId    = $data['instructor_id'] ?? 0;
        $this->instructorName  = $data['instructor_name'] ?? '';
        $this->title           = $data['title'] ?? '';
        $this->description     = $data['description'] ?? '';
        $this->shortDescription = $data['short_description'] ?? '';
        $this->price           = $data['price'] ?? 0;
        $this->category        = $data['category'] ?? '';
        $this->level           = $data['level'] ?? 'beginner';
        $this->duration        = $data['duration'] ?? 0;
        $this->lessonsCount    = $data['lessons_count'] ?? 0;
        $this->thumbnail       = $data['thumbnail'] ?? '/assets/images/courses/default.jpg';
        $this->promoVideo      = $data['promo_video'] ?? '';
        $this->curriculum      = json_decode($data['curriculum'] ?? '[]', true);
        $this->resources       = json_decode($data['resources'] ?? '[]', true);
        $this->status          = $data['status'] ?? 'draft';
        $this->featured        = (bool)($data['featured'] ?? false);
        $this->rating          = $data['rating'] ?? 0;
        $this->enrollmentCount = $data['enrollment_count'] ?? 0;
        $this->createdAt       = $data['created_at'] ?? date('Y-m-d H:i:s');
        $this->updatedAt       = $data['updated_at'] ?? date('Y-m-d H:i:s');
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
        $db = Database::getConnection();
        $curriculumJson = json_encode($this->curriculum);
        $resourcesJson  = json_encode($this->resources);
        $featured = (int)$this->featured;

        if ($this->id) {
            $stmt = $db->prepare("UPDATE courses SET 
                instructor_id = ?, instructor_name = ?, title = ?, description = ?, short_description = ?,
                price = ?, category = ?, level = ?, duration = ?, lessons_count = ?,
                thumbnail = ?, promo_video = ?, curriculum = ?, resources = ?,
                status = ?, featured = ?, rating = ?, enrollment_count = ?, updated_at = NOW()
                WHERE course_id = ?");
            $stmt->bind_param("issssdsssisssssdiii", 
                $this->instructorId, $this->instructorName, $this->title, $this->description, $this->shortDescription,
                $this->price, $this->category, $this->level, $this->duration, $this->lessonsCount,
                $this->thumbnail, $this->promoVideo, $curriculumJson, $resourcesJson,
                $this->status, $featured, $this->rating, $this->enrollmentCount,
                $this->id
            );
        } else {
            $stmt = $db->prepare("INSERT INTO courses 
                (instructor_id, instructor_name, title, description, short_description,
                price, category, level, duration, lessons_count,
                thumbnail, promo_video, curriculum, resources,
                status, featured, rating, enrollment_count, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
            $stmt->bind_param("issssdsssisssssdii", 
                $this->instructorId, $this->instructorName, $this->title, $this->description, $this->shortDescription,
                $this->price, $this->category, $this->level, $this->duration, $this->lessonsCount,
                $this->thumbnail, $this->promoVideo, $curriculumJson, $resourcesJson,
                $this->status, $featured, $this->rating, $this->enrollmentCount
            );
        }
        $stmt->execute();
        if (!$this->id) {
            $this->id = $db->insert_id;
        }
        return true;
    }

    public function addLesson(array $lessonData)
    {
        $lessonData['id'] = uniqid();
        $this->curriculum[] = $lessonData;
        $this->lessonsCount = count($this->curriculum);
        return $this;
    }

    public function deleteLesson($lessonId)
    {
        $this->curriculum = array_values(array_filter($this->curriculum, fn($l) => ($l['id'] ?? '') != $lessonId));
        $this->lessonsCount = count($this->curriculum);
        return $this;
    }

    public static function findById($id)
    {
        $course = new self();
        if ($course->load($id)) return $course;
        return null;
    }

    public static function getAll()
    {
        $db = Database::getConnection();
        $result = $db->query("SELECT * FROM courses ORDER BY created_at DESC");
        $courses = [];
        while ($row = $result->fetch_assoc()) {
            $c = new self();
            $c->hydrate($row);
            $courses[] = $c;
        }
        return $courses;
    }

    public static function getPublished()
    {
        $db = Database::getConnection();
        $result = $db->query("SELECT * FROM courses WHERE status = 'published' ORDER BY created_at DESC");
        $courses = [];
        while ($row = $result->fetch_assoc()) {
            $c = new self();
            $c->hydrate($row);
            $courses[] = $c;
        }
        return $courses;
    }

    public static function getByInstructor($instructorId)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM courses WHERE instructor_id = ?");
        $stmt->bind_param("i", $instructorId);
        $stmt->execute();
        $result = $stmt->get_result();
        $courses = [];
        while ($row = $result->fetch_assoc()) {
            $c = new self();
            $c->hydrate($row);
            $courses[] = $c;
        }
        return $courses;
    }

    public static function getFeatured($limit = 4)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM courses WHERE featured = 1 AND status = 'published' ORDER BY created_at DESC LIMIT ?");
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $courses = [];
        while ($row = $result->fetch_assoc()) {
            $c = new self();
            $c->hydrate($row);
            $courses[] = $c;
        }
        return $courses;
    }

    public static function create($data)
    {
        $course = new self();
        $course->instructorId   = $data['instructor_id'] ?? 0;
        $course->instructorName = $data['instructor_name'] ?? '';
        $course->title          = $data['title'];
        $course->description    = $data['description'] ?? '';
        $course->shortDescription = $data['short_description'] ?? '';
        $course->price          = $data['price'] ?? 0;
        $course->category       = $data['category'] ?? '';
        $course->level          = $data['level'] ?? 'beginner';
        $course->duration       = $data['duration'] ?? 0;
        $course->lessonsCount   = $data['lessons'] ?? 0;
        $course->thumbnail      = $data['thumbnail'] ?? '/assets/images/courses/default.jpg';
        $course->promoVideo     = $data['promo_video'] ?? '';
        $course->curriculum     = $data['curriculum'] ?? [];
        $course->resources      = $data['resources'] ?? [];
        $course->status         = $data['status'] ?? 'draft';
        $course->featured       = (bool)($data['featured'] ?? false);
        $course->rating         = $data['rating'] ?? 0;
        $course->save();
        return $course;
    }

    public function delete()
    {
        if (!$this->id) return false;
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM courses WHERE course_id = ?");
        $stmt->bind_param("i", $this->id);
        return $stmt->execute();
    }
}