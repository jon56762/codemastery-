<?php

class User
{
    public $id;
    public $name;
    public $email;
    public $password;   // hashed
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

    private static $storage;

    // Get our FileStorage object (only create it once)
    private static function getStorage()
    {
        if (!self::$storage) {
            self::$storage = new FileStorage('users.json');
        }
        return self::$storage;
    }

    public function __construct($id, $name, $email, $password, $role = 'student', $status = 'active', $avatar = '/assets/images/avatars/default.png', $skills = [], $bio = '', 
    $learningGoals = '', $notificationPreferences = [], $privacySettings = [], $createdAt = null, $updatedAt = null,)
    {
        $this->id         = $id;
        $this->name       = $name;
        $this->email      = $email;
        $this->password   = $password;
        $this->role       = $role;
        $this->status     = $status;
        $this->avatar     = $avatar;
        $this->skills     = $skills;
        $this->bio        = $bio;

        $this->learningGoals = $learningGoals;
        $this->notificationPreferences = $notificationPreferences;
        $this->privacySettings = $privacySettings;

        $this->createdAt  = $createdAt ?? date('Y-m-d H:i:s');
        $this->updatedAt  = $updatedAt ?? date('Y-m-d H:i:s');
    }

    // Convert object back to array (for JSON saving or sessions)
    public function toArray()
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'email'      => $this->email,
            'password'   => $this->password,
            'role'       => $this->role,
            'status'     => $this->status,
            'avatar'     => $this->avatar,
            'skills'     => $this->skills,
            'bio'        => $this->bio,

            'learning_goals'        => $this->learningGoals,
            'notification_preferences' => $this->notificationPreferences,
            'privacy_settings'      => $this->privacySettings,

            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }

    // Save this user back to the file (update if exists, insert if new)
    public function save()
    {
        $users = self::getStorage()->readAll();
        $found = false;
        foreach ($users as &$u) {
            if ($u['id'] == $this->id) {
                $u = $this->toArray();
                $found = true;
                break;
            }
        }
        if (!$found) {
            $users[] = $this->toArray();
        }
        return self::getStorage()->writeAll($users);
    }

    // Check password
    public function verifyPassword($plainPassword)
    {
        return password_verify($plainPassword, $this->password);
    }

    // Change password
    public function changePassword($newPlainPassword)
    {
        $this->password = password_hash($newPlainPassword, PASSWORD_DEFAULT);
    }

    // ----- Static finders (same as your old functions) -----
    public static function findById($id)
    {
        $data = self::getStorage()->find('id', $id);
        if ($data) {
            return self::fromArray($data);
        }
        return null;
    }

    public static function findByEmail($email)
    {
        $data = self::getStorage()->find('email', $email);
        if ($data) {
            return self::fromArray($data);
        }
        return null;
    }

    public static function getAll()
    {
        $all = self::getStorage()->readAll();
        $users = [];
        foreach ($all as $data) {
            $users[] = self::fromArray($data);
        }
        return $users;
    }

    public static function findByRole($role)
    {
        $filtered = self::getStorage()->where(function ($u) use ($role) {
            return $u['role'] === $role;
        });
        $users = [];
        foreach ($filtered as $data) {
            $users[] = self::fromArray($data);
        }
        return $users;
    }

    // Create a new user and save it
    public static function create($data)
    {
        $storage = self::getStorage();
        $id = $storage->nextId('id');
        $user = new self(
            $id,
            $data['name'],
            $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['role'] ?? 'student',
            'active',
            $data['avatar'] ?? '/assets/images/avatars/default.png',
            $data['skills'] ?? [],
            $data['bio'] ?? ''
        );
        $user->save();
        return $user;
    }

    // Helper to create a User object from an array (used internally)
    private static function fromArray($data)
    {
        return new self(
            $data['id'],
            $data['name'],
            $data['email'],
            $data['password'],
            $data['role'],
            $data['status'],
            $data['avatar'],
            $data['skills'] ?? [],
            $data['bio'] ?? '',

            $data['learning_goals'] ?? '',         
            $data['notification_preferences'] ?? [],
            $data['privacy_settings'] ?? [],
            $data['created_at'],
            $data['updated_at']
        );
    }
}
