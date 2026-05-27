<?php
require_once __DIR__ . '/../database/db.php';

class LessonNote
{
    public $id;
    public $userId;
    public $courseId;
    public $lessonId;
    public $content;
    public $timestamp;
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
        $stmt = $db->prepare("SELECT * FROM lesson_notes WHERE note_id = ?");
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
        $this->id        = $data['note_id'] ?? null;
        $this->userId    = $data['user_id'] ?? 0;
        $this->courseId  = $data['course_id'] ?? 0;
        $this->lessonId  = $data['lesson_id'] ?? '';
        $this->content   = $data['content'] ?? '';
        $this->timestamp = $data['timestamp'] ?? '00:00';
        $this->createdAt = $data['created_at'] ?? date('Y-m-d H:i:s');
        $this->updatedAt = $data['updated_at'] ?? date('Y-m-d H:i:s');
    }

    public function toArray()
    {
        return [
            'id'         => $this->id,
            'user_id'    => $this->userId,
            'course_id'  => $this->courseId,
            'lesson_id'  => $this->lessonId,
            'content'    => $this->content,
            'timestamp'  => $this->timestamp,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }

    public function save()
    {
        $db = Database::getConnection();
        if ($this->id) {
            $stmt = $db->prepare("UPDATE lesson_notes SET 
                user_id = ?, course_id = ?, lesson_id = ?, content = ?, timestamp = ?, updated_at = NOW()
                WHERE note_id = ?");
            $stmt->bind_param("iisssi", $this->userId, $this->courseId, $this->lessonId, $this->content, $this->timestamp, $this->id);
        } else {
            $stmt = $db->prepare("INSERT INTO lesson_notes 
                (user_id, course_id, lesson_id, content, timestamp, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, NOW(), NOW())");
            $stmt->bind_param("iisss", $this->userId, $this->courseId, $this->lessonId, $this->content, $this->timestamp);
        }
        $stmt->execute();
        if (!$this->id) {
            $this->id = $db->insert_id;
        }
        return true;
    }

    public function delete()
    {
        if (!$this->id) return false;
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM lesson_notes WHERE note_id = ?");
        $stmt->bind_param("i", $this->id);
        return $stmt->execute();
    }

    // Static methods
    public static function create($data)
    {
        $note = new self();
        $note->userId    = $data['user_id'];
        $note->courseId  = $data['course_id'];
        $note->lessonId  = $data['lesson_id'];
        $note->content   = $data['content'];
        $note->timestamp = $data['timestamp'] ?? '00:00';
        $note->save();
        return $note;
    }

    public static function findById($id)
    {
        $note = new self();
        return $note->load($id) ? $note : null;
    }

    /**
     * Get notes for a specific user, course, and lesson.
     * Returns an array of LessonNote objects.
     */
    public static function findByUserAndLesson($userId, $courseId, $lessonId)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM lesson_notes 
                              WHERE user_id = ? AND course_id = ? AND lesson_id = ?
                              ORDER BY created_at DESC");
        $stmt->bind_param("iis", $userId, $courseId, $lessonId);
        $stmt->execute();
        $result = $stmt->get_result();
        $notes = [];
        while ($row = $result->fetch_assoc()) {
            $note = new self();
            $note->hydrate($row);
            $notes[] = $note;
        }
        return $notes;
    }
}