<?php

require_once __DIR__ . '/../database/db.php';

class Enrollment
{
    public $id;
    public $courseId;
    public $userId;
    public $enrolledAt;
    public $progress;
    public $completedLessons = [];
    public $status;

    public function __construct($id = null)
    {
        if ($id !== null) {
            $this->load($id);
        }
    }

    private function load($id)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM enrollments WHERE enrollment_id = ?");
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
        $this->id               = $data['enrollment_id'] ?? null;
        $this->courseId         = $data['course_id'] ?? 0;
        $this->userId           = $data['user_id'] ?? 0;
        $this->enrolledAt       = $data['enrolled_at'] ?? date('Y-m-d H:i:s');
        $this->progress         = (int)($data['progress'] ?? 0);
        $this->completedLessons = json_decode($data['completed_lessons'] ?? '[]', true);
        $this->status           = $data['status'] ?? 'active';
    }

    public function toArray()
    {
        return [
            'id'                => $this->id,
            'course_id'         => $this->courseId,
            'user_id'           => $this->userId,
            'enrolled_at'       => $this->enrolledAt,
            'progress'          => $this->progress,
            'completed_lessons' => $this->completedLessons,
            'status'            => $this->status,
        ];
    }

    public function save()
    {
        $db = Database::getConnection();
        $json = json_encode($this->completedLessons);

        if ($this->id) {
            $stmt = $db->prepare("UPDATE enrollments SET 
                course_id = ?, user_id = ?, progress = ?, completed_lessons = ?, status = ?
                WHERE enrollment_id = ?");
            $stmt->bind_param("iiisss", $this->courseId, $this->userId, $this->progress, $json, $this->status, $this->id);
        } else {
            if (!$this->id) {
                $this->id = 'ENR' . date('YmdHis') . rand(1000, 9999);
            }
            $stmt = $db->prepare("INSERT INTO enrollments 
                (enrollment_id, course_id, user_id, enrolled_at, progress, completed_lessons, status)
                VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("siisiss", $this->id, $this->courseId, $this->userId, $this->enrolledAt, $this->progress, $json, $this->status);
        }
        return $stmt->execute();
    }

    public static function enroll($userId, $courseId)
    {
        $existing = self::findByUserAndCourse($userId, $courseId);
        if ($existing) return false;

        $enrollment = new self();
        $enrollment->userId = $userId;
        $enrollment->courseId = $courseId;
        $enrollment->enrolledAt = date('Y-m-d H:i:s');
        $enrollment->progress = 0;
        $enrollment->completedLessons = [];
        $enrollment->status = 'active';
        $enrollment->save();
        return $enrollment;
    }

    public static function findByUserAndCourse($userId, $courseId)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM enrollments WHERE user_id = ? AND course_id = ?");
        $stmt->bind_param("ii", $userId, $courseId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $e = new self();
            $e->hydrate($row);
            return $e;
        }
        return null;
    }

    public static function findByUser($userId)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM enrollments WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $enrollments = [];
        while ($row = $result->fetch_assoc()) {
            $e = new self();
            $e->hydrate($row);
            $enrollments[] = $e;
        }
        return $enrollments;
    }

    public static function findByCourse($courseId)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM enrollments WHERE course_id = ?");
        $stmt->bind_param("i", $courseId);
        $stmt->execute();
        $result = $stmt->get_result();
        $enrollments = [];
        while ($row = $result->fetch_assoc()) {
            $e = new self();
            $e->hydrate($row);
            $enrollments[] = $e;
        }
        return $enrollments;
    }

    public function toggleLesson($lessonId, $completed = true)
    {
        if ($completed && !in_array($lessonId, $this->completedLessons)) {
            $this->completedLessons[] = $lessonId;
        } elseif (!$completed) {
            $this->completedLessons = array_diff($this->completedLessons, [$lessonId]);
        }

        $course = Course::findById($this->courseId);
        if ($course) {
            $total = count($course->curriculum);
            $this->progress = $total > 0 ? round(count($this->completedLessons) / $total * 100) : 0;
        }
        return $this->save();
    }
}
