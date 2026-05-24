<?php

require_once __DIR__ . '/../database/db.php';

class Announcement
{
    public $id;
    public $courseId;
    public $instructorId;
    public $instructorName;
    public $title;
    public $content;
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
        $stmt = $db->prepare("SELECT * FROM announcements WHERE announcement_id = ?");
        $stmt->bind_param("s", $id);
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
        $this->id             = $data['announcement_id'] ?? null;
        $this->courseId       = $data['course_id'] ?? 0;
        $this->instructorId   = $data['instructor_id'] ?? 0;
        $this->instructorName = $data['instructor_name'] ?? '';
        $this->title          = $data['title'] ?? '';
        $this->content        = $data['content'] ?? '';
        $this->createdAt      = $data['created_at'] ?? date('Y-m-d H:i:s');
        $this->updatedAt      = $data['updated_at'] ?? date('Y-m-d H:i:s');
    }

    public function toArray()
    {
        return [
            'id'              => $this->id,
            'course_id'       => $this->courseId,
            'instructor_id'   => $this->instructorId,
            'instructor_name' => $this->instructorName,
            'title'           => $this->title,
            'content'         => $this->content,
            'created_at'      => $this->createdAt,
            'updated_at'      => $this->updatedAt,
        ];
    }

    public function save()
    {
        $db = Database::getConnection();
        if ($this->id) {
            $stmt = $db->prepare("UPDATE announcements SET 
                course_id = ?, instructor_id = ?, instructor_name = ?, title = ?, content = ?, updated_at = NOW()
                WHERE announcement_id = ?");
            $stmt->bind_param("iissss", $this->courseId, $this->instructorId, $this->instructorName, $this->title, $this->content, $this->id);
        } else {
            if (!$this->id) {
                $this->id = uniqid();
            }
            $stmt = $db->prepare("INSERT INTO announcements 
                (announcement_id, course_id, instructor_id, instructor_name, title, content, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
            $stmt->bind_param("siisss", $this->id, $this->courseId, $this->instructorId, $this->instructorName, $this->title, $this->content);
        }
        return $stmt->execute();
    }

    public static function create($data)
    {
        $ann = new self();
        $ann->courseId       = $data['course_id'];
        $ann->instructorId   = $data['instructor_id'];
        $ann->instructorName = $data['instructor_name'] ?? '';
        $ann->title          = $data['title'];
        $ann->content        = $data['content'] ?? '';
        $ann->save();
        return $ann;
    }

    public static function getByCourse($courseId)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM announcements WHERE course_id = ? ORDER BY created_at DESC");
        $stmt->bind_param("i", $courseId);
        $stmt->execute();
        $result = $stmt->get_result();
        $anns = [];
        while ($row = $result->fetch_assoc()) {
            $a = new self();
            $a->hydrate($row);
            $anns[] = $a;
        }
        return $anns;
    }
}