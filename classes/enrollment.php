<?php

class Enrollment
{
    public $id;
    public $courseId;
    public $userId;
    public $enrolledAt;
    public $progress;
    public $completedLessons;
    public $status;

    private static $storage;

    private static function getStorage()
    {
        if (!self::$storage) {
            self::$storage = new FileStorage('enrollments.json');
        }
        return self::$storage;
    }

    public function __construct($id, $courseId, $userId, $enrolledAt, $progress = 0, $completedLessons = [], $status = 'active')
    {
        $this->id               = $id;
        $this->courseId         = $courseId;
        $this->userId           = $userId;
        $this->enrolledAt       = $enrolledAt;
        $this->progress         = $progress;
        $this->completedLessons = $completedLessons;
        $this->status           = $status;
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
        $all = self::getStorage()->readAll();
        $found = false;
        foreach ($all as &$e) {
            if ($e['id'] == $this->id) {
                $e = $this->toArray();
                $found = true;
                break;
            }
        }
        if (!$found) {
            $all[] = $this->toArray();
        }
        return self::getStorage()->writeAll($all);
    }

    public static function enroll($userId, $courseId)
    {
        // Check duplicate
        $existing = self::getStorage()->where(function ($e) use ($userId, $courseId) {
            return $e['user_id'] == $userId && $e['course_id'] == $courseId;
        });
        if (!empty($existing)) return false;

        $id = 'ENR' . date('YmdHis') . rand(1000, 9999);
        $enrollment = new self($id, $courseId, $userId, date('Y-m-d H:i:s'));
        $enrollment->save();
        return $enrollment;
    }

    public static function findByUser($userId)
    {
        $filtered = self::getStorage()->where(function ($e) use ($userId) {
            return $e['user_id'] == $userId;
        });
        $list = [];
        foreach ($filtered as $data) {
            $list[] = self::fromArray($data);
        }
        return $list;
    }

    public static function findByCourse($courseId)
    {
        $filtered = self::getStorage()->where(function ($e) use ($courseId) {
            return $e['course_id'] == $courseId;
        });
        $list = [];
        foreach ($filtered as $data) {
            $list[] = self::fromArray($data);
        }
        return $list;
    }

    public static function isEnrolled($userId, $courseId)
    {
        $res = self::getStorage()->where(function ($e) use ($userId, $courseId) {
            return $e['user_id'] == $userId && $e['course_id'] == $courseId;
        });
        return !empty($res) ? self::fromArray(reset($res)) : false;
    }

    private static function fromArray($data)
    {
        return new self(
            $data['id'],
            $data['course_id'],
            $data['user_id'],
            $data['enrolled_at'],
            $data['progress'] ?? 0,
            $data['completed_lessons'] ?? [],
            $data['status'] ?? 'active'
        );
    }
}