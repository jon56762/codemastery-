<?php
require_once __DIR__ . '/../database/db.php';

class Notification
{
    public $id;
    public $userId;
    public $title;
    public $message;
    public $read;        // 0 or 1
    public $link;
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
        $stmt = $db->prepare("SELECT * FROM notifications WHERE id = ?");
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
        $this->id        = $data['id'] ?? null;
        $this->userId    = $data['user_id'] ?? 0;
        $this->title     = $data['title'] ?? '';
        $this->message   = $data['message'] ?? '';
        $this->read      = $data['read'] ?? 0;
        $this->link      = $data['link'] ?? '';
        $this->createdAt = $data['created_at'] ?? date('Y-m-d H:i:s');
    }

    public function toArray()
    {
        return [
            'id'         => $this->id,
            'user_id'    => $this->userId,
            'title'      => $this->title,
            'message'    => $this->message,
            'read'       => $this->read,
            'link'       => $this->link,
            'created_at' => $this->createdAt,
        ];
    }

    public function save()
    {
        $db = Database::getConnection();
        if ($this->id) {
            $stmt = $db->prepare("UPDATE notifications SET 
                user_id = ?, title = ?, message = ?, `read` = ?, link = ? WHERE id = ?");
            $stmt->bind_param("issisi", $this->userId, $this->title, $this->message, $this->read, $this->link, $this->id);
        } else {
            $stmt = $db->prepare("INSERT INTO notifications 
                (user_id, title, message, `read`, link, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
            $stmt->bind_param("issis", $this->userId, $this->title, $this->message, $this->read, $this->link);
        }
        $stmt->execute();
        if (!$this->id) {
            $this->id = $db->insert_id;
        }
        return true;
    }

    public function markRead()
    {
        $this->read = 1;
        return $this->save();
    }

    // Static methods
    public static function findById($id)
    {
        $n = new self();
        return $n->load($id) ? $n : null;
    }

    public static function findByUser($userId)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $notifications = [];
        while ($row = $result->fetch_assoc()) {
            $n = new self();
            $n->hydrate($row);
            $notifications[] = $n;
        }
        return $notifications;
    }

    public static function countUnread($userId)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM notifications WHERE user_id = ? AND `read` = 0");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'] ?? 0;
    }
}