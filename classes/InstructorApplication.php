<?php

class InstructorApplication
{
    public $id;
    public $userId;
    public $name;
    public $email;
    public $experience;
    public $specialization;
    public $portfolio;
    public $linkedin;
    public $status;
    public $submittedAt;
    public $reviewedAt;
    public $reviewedBy;
    public $notes;

    private static $storage;

    private static function getStorage()
    {
        if (!self::$storage) {
            self::$storage = new FileStorage('instructor-applications.json');
        }
        return self::$storage;
    }

    public function __construct($id, $userId, $name, $email, $experience, $specialization, $portfolio, $linkedin, $status = 'pending', $submittedAt = null, $reviewedAt = null, $reviewedBy = null, $notes = '')
    {
        $this->id             = $id;
        $this->userId         = $userId;
        $this->name           = $name;
        $this->email          = $email;
        $this->experience     = $experience;
        $this->specialization = $specialization;
        $this->portfolio      = $portfolio;
        $this->linkedin       = $linkedin;
        $this->status         = $status;
        $this->submittedAt    = $submittedAt ?? date('Y-m-d H:i:s');
        $this->reviewedAt     = $reviewedAt;
        $this->reviewedBy     = $reviewedBy;
        $this->notes          = $notes;
    }

    public function toArray()
    {
        return [
            'id'             => $this->id,
            'user_id'        => $this->userId,
            'name'           => $this->name,
            'email'          => $this->email,
            'experience'     => $this->experience,
            'specialization' => $this->specialization,
            'portfolio'      => $this->portfolio,
            'linkedin'       => $this->linkedin,
            'status'         => $this->status,
            'submitted_at'   => $this->submittedAt,
            'reviewed_at'    => $this->reviewedAt,
            'reviewed_by'    => $this->reviewedBy,
            'notes'          => $this->notes,
        ];
    }

    public function save()
    {
        $all = self::getStorage()->readAll();
        $found = false;
        foreach ($all as &$a) {
            if ($a['id'] == $this->id) {
                $a = $this->toArray();
                $found = true;
                break;
            }
        }
        if (!$found) {
            $all[] = $this->toArray();
        }
        return self::getStorage()->writeAll($all);
    }

    public static function submit($data)
    {
        $storage = self::getStorage();
        $id = 'APP' . date('YmdHis') . rand(1000,9999);
        $app = new self(
            $id,
            $data['user_id'] ?? 0,
            $data['name'],
            $data['email'],
            $data['experience'],
            $data['specialization'],
            $data['portfolio'] ?? '',
            $data['linkedin'] ?? ''
        );
        $app->save();
        return $app;
    }

    public static function getAll()
    {
        $all = self::getStorage()->readAll();
        $apps = [];
        foreach ($all as $data) {
            $apps[] = self::fromArray($data);
        }
        return $apps;
    }

    public static function findById($id)
    {
        $data = self::getStorage()->find('id', $id);
        return $data ? self::fromArray($data) : null;
    }

    public static function approve($id, $reviewerId)
    {
        $app = self::findById($id);
        if ($app) {
            $app->status = 'approved';
            $app->reviewedAt = date('Y-m-d H:i:s');
            $app->reviewedBy = $reviewerId;
            $app->notes = 'Approved';
            $app->save();
            // Promote user role (example – you can call a User method)
            return true;
        }
        return false;
    }

    // Reject, etc.

    private static function fromArray($data)
    {
        return new self(
            $data['id'],
            $data['user_id'],
            $data['name'],
            $data['email'],
            $data['experience'],
            $data['specialization'],
            $data['portfolio'] ?? '',
            $data['linkedin'] ?? '',
            $data['status'],
            $data['submitted_at'],
            $data['reviewed_at'],
            $data['reviewed_by'],
            $data['notes']
        );
    }
}