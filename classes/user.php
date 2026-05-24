<?php
require_once __DIR__ . '/../database/db.php';

class User
{
    public $id;
    public $name;
    public $email;
    public $password;
    public $role;
    public $status;
    public $avatar;
    public $skills;
    public $bio;
    public $learningGoals;
    public $notificationPreferences;
    public $privacySettings;
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
        $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
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
        $this->id                   = $data['id'] ?? null;
        $this->name                 = $data['name'] ?? '';
        $this->email                = $data['email'] ?? '';
        $this->password             = $data['password'] ?? '';
        $this->role                 = $data['role'] ?? 'student';
        $this->status               = $data['status'] ?? 'active';
        $this->avatar               = $data['avatar'] ?? '/assets/images/avatars/default.png';
        $this->skills               = json_decode($data['skills'] ?? '[]', true);
        $this->bio                  = $data['bio'] ?? '';
        $this->learningGoals        = $data['learning_goals'] ?? '';
        $this->notificationPreferences = json_decode($data['notification_preferences'] ?? '{}', true);
        $this->privacySettings      = json_decode($data['privacy_settings'] ?? '{}', true);
        $this->createdAt            = $data['created_at'] ?? date('Y-m-d H:i:s');
        $this->updatedAt            = $data['updated_at'] ?? date('Y-m-d H:i:s');
    }

    public function toArray()
    {
        return [
            'id'                      => $this->id,
            'name'                    => $this->name,
            'email'                   => $this->email,
            'password'                => $this->password,
            'role'                    => $this->role,
            'status'                  => $this->status,
            'avatar'                  => $this->avatar,
            'skills'                  => $this->skills,
            'bio'                     => $this->bio,
            'learning_goals'          => $this->learningGoals,
            'notification_preferences'=> $this->notificationPreferences,
            'privacy_settings'        => $this->privacySettings,
            'created_at'              => $this->createdAt,
            'updated_at'              => $this->updatedAt,
        ];
    }

    public function save()
    {
        $db = Database::getConnection();
        $skillsJson = json_encode($this->skills);
        $notifJson  = json_encode($this->notificationPreferences);
        $privJson   = json_encode($this->privacySettings);

        if ($this->id) {
            $stmt = $db->prepare("UPDATE users SET 
                name = ?, email = ?, password = ?, role = ?, status = ?, avatar = ?,
                skills = ?, bio = ?, learning_goals = ?, notification_preferences = ?,
                privacy_settings = ?, updated_at = NOW()
                WHERE id = ?");
            $stmt->bind_param(
                "sssssssssssi",
                $this->name,
                $this->email,
                $this->password,
                $this->role,
                $this->status,
                $this->avatar,
                $skillsJson,
                $this->bio,
                $this->learningGoals,
                $notifJson,
                $privJson,
                $this->id
            );
        } else {
            $stmt = $db->prepare("INSERT INTO users 
                (name, email, password, role, status, avatar, skills, bio, learning_goals, notification_preferences, privacy_settings, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
            $stmt->bind_param(
                "sssssssssss",
                $this->name,
                $this->email,
                $this->password,
                $this->role,
                $this->status,
                $this->avatar,
                $skillsJson,
                $this->bio,
                $this->learningGoals,
                $notifJson,
                $privJson
            );
        }
        $stmt->execute();
        if (!$this->id) {
            $this->id = $db->insert_id;
        }
        return true;
    }

    public function verifyPassword($plainPassword)
    {
        return password_verify($plainPassword, $this->password);
    }

    public function changePassword($newPlainPassword)
    {
        $this->password = password_hash($newPlainPassword, PASSWORD_DEFAULT);
    }

    // Static finders
    public static function findById($id)
    {
        $user = new self();
        if ($user->load($id)) {
            return $user;
        }
        return null;
    }

    public static function findByEmail($email)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $user = new self();
            $user->hydrate($row);
            return $user;
        }
        return null;
    }

    public static function getAll()
    {
        $db = Database::getConnection();
        $result = $db->query("SELECT * FROM users ORDER BY created_at DESC");
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $user = new self();
            $user->hydrate($row);
            $users[] = $user;
        }
        return $users;
    }

    public static function findByRole($role)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE role = ?");
        $stmt->bind_param("s", $role);
        $stmt->execute();
        $result = $stmt->get_result();
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $user = new self();
            $user->hydrate($row);
            $users[] = $user;
        }
        return $users;
    }

    public static function create($data)
    {
        $user = new self();
        $user->name     = $data['name'];
        $user->email    = $data['email'];
        $user->password = password_hash($data['password'], PASSWORD_DEFAULT);
        $user->role     = $data['role'] ?? 'student';
        $user->status   = 'active';
        $user->avatar   = $data['avatar'] ?? '/assets/images/avatars/default.png';
        $user->skills   = $data['skills'] ?? [];
        $user->bio      = $data['bio'] ?? '';
        $user->save();  // sets $user->id after insert
        return $user;
    }

    public function delete()
    {
        if (!$this->id) return false;
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $this->id);
        return $stmt->execute();
    }
}