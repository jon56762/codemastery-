// Enhanced lesson type field management
function showLessonFields(lessonType, formType) {
    const fieldsContainer = document.getElementById(formType === 'add' ? 'lesson-type-fields' : 'edit-lesson-type-fields');
    let html = '';

    switch(lessonType) {
        case 'video':
            html = `
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-link me-2"></i>Video URL
                    </label>
                    <input type="url" class="form-control" id="${formType}_video_url" name="${formType}_video_url" 
                        placeholder="https://youtube.com/embed/... or https://vimeo.com/...">
                    <div class="form-text">
                        <i class="fas fa-info-circle me-1"></i>Paste YouTube or Vimeo embed URL
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-upload me-2"></i>Or Upload Video File
                    </label>
                    <input type="file" class="form-control" id="${formType}_video_upload" name="video_upload" accept="video/*">
                    <div class="form-text">
                        <i class="fas fa-info-circle me-1"></i>Max file size: 100MB. Supported formats: MP4, MOV, AVI
                    </div>
                </div>
                <div class="mb-3">
                    <label for="${formType}_lesson_content" class="form-label fw-semibold">Video Notes/Description</label>
                    <textarea class="form-control" id="${formType}_lesson_content" name="${formType}_lesson_content" rows="4"
                        placeholder="Additional notes, timestamps, or description for this video..."></textarea>
                </div>
            `;
            break;

        case 'reading':
            html = `
                <div class="mb-3">
                    <label for="${formType}_lesson_content" class="form-label fw-semibold">Reading Content *</label>
                    <textarea class="form-control rich-text-editor" id="${formType}_lesson_content" name="${formType}_lesson_content" rows="10"
                        placeholder="Enter your reading material content here..."></textarea>
                </div>
                <div class="mb-3">
                    <label for="${formType}_reading_time" class="form-label fw-semibold">Estimated Reading Time</label>
                    <input type="text" class="form-control" id="${formType}_reading_time" name="${formType}_reading_time" 
                        placeholder="e.g., 15 min, 30-45 min">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-paperclip me-2"></i>Additional Resources
                    </label>
                    <input type="file" class="form-control" name="reading_resources[]" multiple>
                    <div class="form-text">
                        <i class="fas fa-info-circle me-1"></i>Upload PDFs, documents, or other resources (multiple files allowed)
                    </div>
                </div>
            `;
            break;

        case 'quiz':
            html = `
                <div class="mb-3">
                    <label for="${formType}_quiz_instructions" class="form-label fw-semibold">Quiz Instructions</label>
                    <textarea class="form-control" id="${formType}_quiz_instructions" name="${formType}_quiz_instructions" rows="3"
                        placeholder="Instructions for students taking this quiz..."></textarea>
                </div>
                <div class="mb-3">
                    <label for="${formType}_quiz_questions" class="form-label fw-semibold">Quiz Questions (JSON Format)</label>
                    <textarea class="form-control" id="${formType}_quiz_questions" name="${formType}_quiz_questions" rows="8"
                        placeholder='[{"question": "What is PHP?", "type": "multiple_choice", "options": ["A programming language", "A database", "A framework"], "correct_answer": 0, "points": 10}]'></textarea>
                    <div class="form-text">
                        <i class="fas fa-info-circle me-1"></i>Use JSON format. Question types: multiple_choice, true_false, short_answer
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="${formType}_passing_score" class="form-label fw-semibold">Passing Score (%)</label>
                        <input type="number" class="form-control" id="${formType}_passing_score" name="${formType}_passing_score" 
                            min="0" max="100" value="70">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="${formType}_time_limit" class="form-label fw-semibold">Time Limit (minutes)</label>
                        <input type="number" class="form-control" id="${formType}_time_limit" name="${formType}_time_limit" 
                            min="0" value="0" placeholder="0 for no limit">
                    </div>
                </div>
            `;
            break;

        case 'exercise':
            html = `
                <div class="mb-3">
                    <label for="${formType}_exercise_instructions" class="form-label fw-semibold">Exercise Instructions *</label>
                    <textarea class="form-control rich-text-editor" id="${formType}_exercise_instructions" name="${formType}_exercise_instructions" rows="6"
                        placeholder="Detailed instructions for the hands-on exercise..."></textarea>
                </div>
                <div class="mb-3">
                    <label for="${formType}_starter_code" class="form-label fw-semibold">Starter Code (Optional)</label>
                    <textarea class="form-control code-editor" id="${formType}_starter_code" name="${formType}_starter_code" rows="8"
                        placeholder="Initial code for students to start with..."></textarea>
                </div>
                <div class="mb-3">
                    <label for="${formType}_solution_code" class="form-label fw-semibold">Solution Code (Optional)</label>
                    <textarea class="form-control code-editor" id="${formType}_solution_code" name="${formType}_solution_code" rows="8"
                        placeholder="Complete solution code..."></textarea>
                </div>
                <div class="mb-3">
                    <label for="${formType}_exercise_hints" class="form-label fw-semibold">Hints (one per line)</label>
                    <textarea class="form-control" id="${formType}_exercise_hints" name="${formType}_exercise_hints" rows="4"
                        placeholder="Provide helpful hints for students..."></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-paperclip me-2"></i>Exercise Resources
                    </label>
                    <input type="file" class="form-control" name="exercise_resources[]" multiple>
                    <div class="form-text">
                        <i class="fas fa-info-circle me-1"></i>Upload starter files, datasets, or other resources
                    </div>
                </div>
            `;
            break;
    }

    fieldsContainer.innerHTML = html;
    
    // Initialize editors if needed
    if (lessonType === 'reading' || lessonType === 'exercise') {
        initializeRichTextEditors();
    }
    if (lessonType === 'exercise') {
        initializeCodeEditors();
    }
}

// Enhanced edit lesson functionality
function editLesson(lessonData) {
    // Populate common fields
    document.getElementById('edit_lesson_id').value = lessonData.id;
    document.getElementById('edit_lesson_title').value = lessonData.title;
    document.getElementById('edit_lesson_duration').value = lessonData.duration;
    document.getElementById('edit_lesson_description').value = lessonData.description || '';
    document.getElementById('edit_lesson_type').value = lessonData.type;
    
    // Show type-specific fields and populate them
    showLessonFields(lessonData.type, 'edit');
    
    // Populate type-specific fields
    setTimeout(() => {
        switch(lessonData.type) {
            case 'video':
                document.getElementById('edit_video_url').value = lessonData.video_url || '';
                document.getElementById('edit_lesson_content').value = lessonData.content || '';
                break;
            case 'reading':
                document.getElementById('edit_lesson_content').value = lessonData.content || '';
                document.getElementById('edit_reading_time').value = lessonData.reading_time || '';
                break;
            case 'quiz':
                document.getElementById('edit_quiz_instructions').value = lessonData.instructions || '';
                document.getElementById('edit_quiz_questions').value = JSON.stringify(lessonData.questions || [], null, 2);
                document.getElementById('edit_passing_score').value = lessonData.passing_score || 70;
                document.getElementById('edit_time_limit').value = lessonData.time_limit || 0;
                break;
            case 'exercise':
                document.getElementById('edit_exercise_instructions').value = lessonData.instructions || '';
                document.getElementById('edit_starter_code').value = lessonData.starter_code || '';
                document.getElementById('edit_solution_code').value = lessonData.solution_code || '';
                document.getElementById('edit_exercise_hints').value = (lessonData.hints || []).join('\n');
                break;
        }
    }, 100);
    
    // Show edit modal
    new bootstrap.Modal(document.getElementById('editLessonModal')).show();
}

// Initialize editors (you can use TinyMCE, CodeMirror, or simple textareas)
function initializeRichTextEditors() {
    // Initialize your rich text editor here
    // Example: tinymce.init({ selector: '.rich-text-editor' });
}

function initializeCodeEditors() {
    // Initialize your code editor here  
    // Example: CodeMirror.fromTextArea(document.getElementById('starter_code'), { mode: 'javascript' });
}

// Update the edit button click handlers in your curriculum list
document.addEventListener('DOMContentLoaded', function() {
    const editButtons = document.querySelectorAll('.edit-lesson');
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const lessonData = JSON.parse(this.getAttribute('data-lesson'));
            editLesson(lessonData);
        });
    });
});