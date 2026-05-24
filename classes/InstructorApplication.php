<?php

require_once __DIR__ . '/../database/db.php';

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

    public function __construct($id = null)
    {
        if ($id !== null) {
            $this->load($id);
        }
    }

    private function load($id)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM instructor_applications WHERE application_id = ?");
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
        $this->id             = $data['application_id'] ?? null;
        $this->userId         = $data['user_id'] ?? 0;
        $this->name           = $data['name'] ?? '';
        $this->email          = $data['email'] ?? '';
        $this->experience     = $data['experience'] ?? '';
        $this->specialization = $data['specialization'] ?? '';
        $this->portfolio      = $data['portfolio'] ?? '';
        $this->linkedin       = $data['linkedin'] ?? '';
        $this->status         = $data['status'] ?? 'pending';
        $this->submittedAt    = $data['submitted_at'] ?? date('Y-m-d H:i:s');
        $this->reviewedAt     = $data['reviewed_at'] ?? null;
        $this->reviewedBy     = $data['reviewed_by'] ?? null;
        $this->notes          = $data['notes'] ?? '';
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
        $db = Database::getConnection();
        if ($this->id) {
            $stmt = $db->prepare("UPDATE instructor_applications SET 
                user_id = ?, name = ?, email = ?, experience = ?, specialization = ?,
                portfolio = ?, linkedin = ?, status = ?, submitted_at = ?, reviewed_at = ?,
                reviewed_by = ?, notes = ? WHERE application_id = ?");
            $stmt->bind_param("isssssssssiss", 
                $this->userId, $this->name, $this->email, $this->experience, $this->specialization,
                $this->portfolio, $this->linkedin, $this->status, $this->submittedAt, $this->reviewedAt,
                $this->reviewedBy, $this->notes, $this->id
            );
        } else {
            if (!$this->id) {
                $this->id = 'APP' . date('YmdHis') . rand(1000,9999);
            }
            $stmt = $db->prepare("INSERT INTO instructor_applications 
                (application_id, user_id, name, email, experience, specialization, portfolio, linkedin, status, submitted_at, reviewed_at, reviewed_by, notes)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sisssssssssis", 
                $this->id, $this->userId, $this->name, $this->email, $this->experience, $this->specialization,
                $this->portfolio, $this->linkedin, $this->status, $this->submittedAt, $this->reviewedAt,
                $this->reviewedBy, $this->notes
            );
        }
        return $stmt->execute();
    }

    public static function submit($data)
    {
        $app = new self();
        $app->userId         = $data['user_id'] ?? 0;
        $app->name           = $data['name'];
        $app->email          = $data['email'];
        $app->experience     = $data['experience'] ?? '';
        $app->specialization = $data['specialization'] ?? '';
        $app->portfolio      = $data['portfolio'] ?? '';
        $app->linkedin       = $data['linkedin'] ?? '';
        $app->status         = 'pending';
        $app->submittedAt    = date('Y-m-d H:i:s');
        $app->save();
        return $app;
    }

    public static function getAll()
    {
        $db = Database::getConnection();
        $result = $db->query("SELECT * FROM instructor_applications ORDER BY submitted_at DESC");
        $apps = [];
        while ($row = $result->fetch_assoc()) {
            $a = new self();
            $a->hydrate($row);
            $apps[] = $a;
        }
        return $apps;
    }

    public static function findById($id)
    {
        $app = new self();
        return $app->load($id) ? $app : null;
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
            return true;
        }
        return false;
    }

    public static function reject($id, $reviewerId, $reason = '')
    {
        $app = self::findById($id);
        if ($app) {
            $app->status = 'rejected';
            $app->reviewedAt = date('Y-m-d H:i:s');
            $app->reviewedBy = $reviewerId;
            $app->notes = $reason ?: 'Rejected';
            $app->save();
            return true;
        }
        return false;
    }
}