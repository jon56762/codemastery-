<?php

require_once __DIR__ . '/../database/db.php';

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

    public function __construct($id = null)
    {
        if ($id !== null) {
            $this->load($id);
        }
    }

    private function load($id)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM testimonials WHERE testimonial_id = ?");
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
        $this->id        = $data['testimonial_id'] ?? null;
        $this->userId    = $data['user_id'] ?? 0;
        $this->name      = $data['name'] ?? '';
        $this->role      = $data['role'] ?? '';
        $this->avatar    = $data['avatar'] ?? '';
        $this->text      = $data['text'] ?? '';
        $this->rating    = $data['rating'] ?? 5;
        $this->status    = $data['status'] ?? 'pending';
        $this->createdAt = $data['created_at'] ?? date('Y-m-d H:i:s');
    }

    public function toArray()
    {
        return [
            'id'         => $this->id,
            'user_id'    => $this->userId,
            'name'       => $this->name,
            'role'       => $this->role,
            'avatar'     => $this->avatar,
            'text'       => $this->text,
            'rating'     => $this->rating,
            'status'     => $this->status,
            'created_at' => $this->createdAt,
        ];
    }

    public function save()
    {
        $db = Database::getConnection();
        if ($this->id) {
            $stmt = $db->prepare("UPDATE testimonials SET 
                user_id = ?, name = ?, role = ?, avatar = ?, text = ?, rating = ?, status = ?, created_at = ?
                WHERE testimonial_id = ?");
            $stmt->bind_param("issssissi", $this->userId, $this->name, $this->role, $this->avatar, $this->text, $this->rating, $this->status, $this->createdAt, $this->id);
        } else {
            $stmt = $db->prepare("INSERT INTO testimonials 
                (user_id, name, role, avatar, text, rating, status, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
            $stmt->bind_param("issssis", $this->userId, $this->name, $this->role, $this->avatar, $this->text, $this->rating, $this->status);
            $stmt->execute();
            $this->id = $db->insert_id;
        }
        return $stmt->execute();
    }

    public static function submit($data)
    {
        $t = new self();
        $t->userId = $data['user_id'];
        $t->name   = $data['name'];
        $t->role   = $data['role'];
        $t->avatar = $data['avatar'] ?? '';
        $t->text   = $data['text'];
        $t->rating = $data['rating'] ?? 5;
        $t->status = 'pending';
        $t->save();
        return $t;
    }

    public static function getAll()
    {
        $db = Database::getConnection();
        $result = $db->query("SELECT * FROM testimonials ORDER BY created_at DESC");
        $list = [];
        while ($row = $result->fetch_assoc()) {
            $t = new self();
            $t->hydrate($row);
            $list[] = $t;
        }
        return $list;
    }

    public static function getApproved()
    {
        $db = Database::getConnection();
        $result = $db->query("SELECT * FROM testimonials WHERE status = 'approved' ORDER BY created_at DESC");
        $list = [];
        while ($row = $result->fetch_assoc()) {
            $t = new self();
            $t->hydrate($row);
            $list[] = $t;
        }
        return $list;
    }

    public static function approve($id)
    {
        $t = self::findById($id);
        if ($t) {
            $t->status = 'approved';
            $t->save();
            return true;
        }
        return false;
    }

    public static function reject($id)
    {
        $t = self::findById($id);
        if ($t) {
            $t->status = 'rejected';
            $t->save();
            return true;
        }
        return false;
    }

    public static function findById($id)
    {
        $t = new self();
        return $t->load($id) ? $t : null;
    }
}