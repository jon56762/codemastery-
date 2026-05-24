<?php

require_once __DIR__ . '/../database/db.php';

class BlogPost
{
    public $id;
    public $title;
    public $excerpt;
    public $content;
    public $author;
    public $authorId;
    public $category;
    public $image;
    public $status;
    public $views;
    public $likes = [];
    public $publishedAt;
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
        $stmt = $db->prepare("SELECT * FROM blog_posts WHERE post_id = ?");
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
        $this->id          = $data['post_id'] ?? null;
        $this->title       = $data['title'] ?? '';
        $this->excerpt     = $data['excerpt'] ?? '';
        $this->content     = $data['content'] ?? '';
        $this->author      = $data['author'] ?? '';
        $this->authorId    = $data['author_id'] ?? null;
        $this->category    = $data['category'] ?? '';
        $this->image       = $data['image'] ?? '/assets/images/blog/default.jpg';
        $this->status      = $data['status'] ?? 'pending';
        $this->views       = $data['views'] ?? 0;
        $this->likes       = json_decode($data['likes'] ?? '[]', true);
        $this->publishedAt = $data['published_at'] ?? date('Y-m-d H:i:s');
        $this->createdAt   = $data['created_at'] ?? date('Y-m-d H:i:s');
        $this->updatedAt   = $data['updated_at'] ?? date('Y-m-d H:i:s');
    }

    public function toArray()
    {
        return [
            'id'           => $this->id,
            'title'        => $this->title,
            'excerpt'      => $this->excerpt,
            'content'      => $this->content,
            'author'       => $this->author,
            'author_id'    => $this->authorId,
            'category'     => $this->category,
            'image'        => $this->image,
            'status'       => $this->status,
            'views'        => $this->views,
            'likes'        => $this->likes,
            'published_at' => $this->publishedAt,
            'created_at'   => $this->createdAt,
            'updated_at'   => $this->updatedAt,
        ];
    }

    public function save()
    {
        $db = Database::getConnection();
        $likesJson = json_encode($this->likes);

        if ($this->id) {
            $stmt = $db->prepare("UPDATE blog_posts SET 
                title = ?, excerpt = ?, content = ?, author = ?, author_id = ?, category = ?,
                image = ?, status = ?, views = ?, likes = ?, published_at = ?, updated_at = NOW()
                WHERE post_id = ?");
            $stmt->bind_param("ssssissssssi", 
                $this->title, $this->excerpt, $this->content, $this->author, $this->authorId, $this->category,
                $this->image, $this->status, $this->views, $likesJson, $this->publishedAt, $this->id
            );
        } else {
            $stmt = $db->prepare("INSERT INTO blog_posts 
                (title, excerpt, content, author, author_id, category, image, status, views, likes, published_at, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
            $stmt->bind_param("ssssissssis", 
                $this->title, $this->excerpt, $this->content, $this->author, $this->authorId, $this->category,
                $this->image, $this->status, $this->views, $likesJson, $this->publishedAt
            );
        }
        $stmt->execute();
        if (!$this->id) {
            $this->id = $db->insert_id;
        }
        return true;
    }

    public static function create($data)
    {
        $post = new self();
        $post->title   = $data['title'];
        $post->excerpt = $data['excerpt'] ?? '';
        $post->content = $data['content'] ?? '';
        $post->author  = $data['author'] ?? '';
        $post->authorId = $data['author_id'] ?? null;
        $post->category = $data['category'] ?? '';
        $post->image   = $data['image'] ?? '/assets/images/blog/default.jpg';
        $post->status  = $data['status'] ?? 'pending';
        $post->likes   = [];
        $post->publishedAt = date('Y-m-d H:i:s');
        $post->save();
        return $post;
    }

    public static function getAll()
    {
        $db = Database::getConnection();
        $result = $db->query("SELECT * FROM blog_posts ORDER BY published_at DESC");
        $posts = [];
        while ($row = $result->fetch_assoc()) {
            $p = new self();
            $p->hydrate($row);
            $posts[] = $p;
        }
        return $posts;
    }

    public static function getByStatus($status)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM blog_posts WHERE status = ? ORDER BY published_at DESC");
        $stmt->bind_param("s", $status);
        $stmt->execute();
        $result = $stmt->get_result();
        $posts = [];
        while ($row = $result->fetch_assoc()) {
            $p = new self();
            $p->hydrate($row);
            $posts[] = $p;
        }
        return $posts;
    }

    public static function findById($id)
    {
        $p = new self();
        return $p->load($id) ? $p : null;
    }

    public function like($userId)
    {
        if (!in_array($userId, $this->likes)) {
            $this->likes[] = $userId;
        }
        return $this;
    }

    public function unlike($userId)
    {
        $this->likes = array_diff($this->likes, [$userId]);
        return $this;
    }
}