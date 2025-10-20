<link rel="stylesheet" href="/assets/css/blog-creation.css">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="text-center mb-5">
                <h1 class="fw-bold mb-3">Create Blog Post</h1>
                <p class="text-muted">Share your knowledge with our community</p>
            </div>

            <!-- Creation Form -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" id="blogForm">
                        <div class="mb-4">
                            <label for="title" class="form-label fw-semibold">Post Title *</label>
                            <input type="text" class="form-control" id="title" name="title" 
                                   value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" 
                                   placeholder="Enter a compelling title..." required>
                        </div>

                        <div class="mb-4">
                            <label for="excerpt" class="form-label fw-semibold">Excerpt *</label>
                            <textarea class="form-control" id="excerpt" name="excerpt" rows="3" 
                                      placeholder="Brief description of your post..." required><?= htmlspecialchars($_POST['excerpt'] ?? '') ?></textarea>
                            <div class="form-text">This will be shown in the blog listing.</div>
                        </div>

                        <div class="mb-4">
                            <label for="content" class="form-label fw-semibold">Content *</label>
                            <textarea class="form-control" id="content" name="content" rows="12" 
                                      placeholder="Write your blog post content here..." required><?= htmlspecialchars($_POST['content'] ?? '') ?></textarea>
                            <div class="form-text">You can use basic HTML tags for formatting.</div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="category" class="form-label fw-semibold">Category *</label>
                                <select class="form-select" id="category" name="category" required>
                                    <option value="">Select a category</option>
                                    <option value="Web Development" <?= ($_POST['category'] ?? '') === 'Web Development' ? 'selected' : '' ?>>Web Development</option>
                                    <option value="Data Science" <?= ($_POST['category'] ?? '') === 'Data Science' ? 'selected' : '' ?>>Data Science</option>
                                    <option value="Mobile Development" <?= ($_POST['category'] ?? '') === 'Mobile Development' ? 'selected' : '' ?>>Mobile Development</option>
                                    <option value="Machine Learning" <?= ($_POST['category'] ?? '') === 'Machine Learning' ? 'selected' : '' ?>>Machine Learning</option>
                                    <option value="Career Tips" <?= ($_POST['category'] ?? '') === 'Career Tips' ? 'selected' : '' ?>>Career Tips</option>
                                    <option value="Learning Strategies" <?= ($_POST['category'] ?? '') === 'Learning Strategies' ? 'selected' : '' ?>>Learning Strategies</option>
                                    <option value="Industry News" <?= ($_POST['category'] ?? '') === 'Industry News' ? 'selected' : '' ?>>Industry News</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="image" class="form-label fw-semibold">Featured Image URL</label>
                                <input type="url" class="form-control" id="image" name="image" 
                                       value="<?= htmlspecialchars($_POST['image'] ?? '') ?>" 
                                       placeholder="https://example.com/image.jpg">
                                <div class="form-text">Optional. Leave empty for default image.</div>
                            </div>
                        </div>

                        <!-- Preview Section -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <label class="form-label fw-semibold">Live Preview</label>
                                <button type="button" class="btn btn-sm btn-outline-dark" onclick="updatePreview()">
                                    <i class="fas fa-sync-alt me-1"></i>Refresh Preview
                                </button>
                            </div>
                            <div class="border rounded p-3 bg-light" id="preview" style="min-height: 100px;">
                                <p class="text-muted mb-0">Preview will appear here...</p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-3">
                            <button type="submit" name="create_post" class="btn btn-dark btn-lg flex-grow-1">
                                <i class="fas fa-paper-plane me-2"></i>Publish Post
                            </button>
                            <a href="/blog" class="btn btn-outline-dark btn-lg">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Writing Tips -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-lightbulb me-2"></i>Writing Tips
                    </h5>
                    <ul class="list-unstyled text-muted">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Keep titles clear and engaging</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Write a compelling excerpt to attract readers</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Use headings and paragraphs to structure your content</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Include code examples when relevant</li>
                        <li><i class="fas fa-check text-success me-2"></i>Proofread before publishing</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updatePreview() {
    const title = document.getElementById('title').value;
    const excerpt = document.getElementById('excerpt').value;
    const content = document.getElementById('content').value;
    const category = document.getElementById('category').value;
    
    let previewHTML = '';
    
    if (title) {
        previewHTML += `<h4 class="fw-bold">${escapeHtml(title)}</h4>`;
    }
    
    if (category) {
        previewHTML += `<span class="badge bg-light text-dark mb-3">${escapeHtml(category)}</span>`;
    }
    
    if (excerpt) {
        previewHTML += `<p class="text-muted fst-italic">"${escapeHtml(excerpt)}"</p>`;
    }
    
    if (content) {
        // Simple content preview (first 200 characters)
        const contentPreview = content.length > 200 ? content.substring(0, 200) + '...' : content;
        previewHTML += `<div class="border-top pt-3 mt-3">${escapeHtml(contentPreview)}</div>`;
    }
    
    if (!previewHTML) {
        previewHTML = '<p class="text-muted mb-0">Preview will appear here...</p>';
    }
    
    document.getElementById('preview').innerHTML = previewHTML;
}

function escapeHtml(unsafe) {
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

// Update preview on input
document.getElementById('title').addEventListener('input', updatePreview);
document.getElementById('excerpt').addEventListener('input', updatePreview);
document.getElementById('content').addEventListener('input', updatePreview);
document.getElementById('category').addEventListener('change', updatePreview);

// Initial preview
updatePreview();
</script>