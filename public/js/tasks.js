// Global Active Task ID Variable //
let currentTaskId = null;

// Add Task Modal Handlers //
let taskModal = document.getElementById('taskModal');
let openTaskBtn = document.getElementById('openTaskModal');
let closeTaskBtn = document.getElementById('closeTaskModal');
let cancelTaskBtn = document.getElementById('cancelTaskModal');

if (openTaskBtn) openTaskBtn.onclick = () => taskModal.classList.remove('hidden');
if (closeTaskBtn) closeTaskBtn.onclick = () => taskModal.classList.add('hidden');
if (cancelTaskBtn) cancelTaskBtn.onclick = () => taskModal.classList.add('hidden');

function openTaskDetail(element) {
    try {
        let rawData = element.getAttribute('data-task');
        if (!rawData) return;

        let task = JSON.parse(rawData);
        currentTaskId = task.id;

        document.getElementById('detailSummary').innerText = task.summary || 'No Summary';
        document.getElementById('detailDescription').innerText = task.description || 'No description provided.';

        document.getElementById('detailProject').innerText = task.project ? task.project.name : 'NO PROJECT';

        let statusObj = task.status || task.task_status;
        let statusName = statusObj ? statusObj.name : 'To Do';
        let statusColor = statusObj ? statusObj.color : '#3B82F6';

        let statusTextEl = document.getElementById('detailStatus');
        if (statusTextEl) {
            statusTextEl.innerText = statusName;
        }

        let statusDotEl = document.getElementById('detailStatusDot');
        if (statusDotEl) {
            statusDotEl.style.backgroundColor = statusColor;
        }

        document.getElementById('detailPriority').innerText = task.priority || 'Medium';
        document.getElementById('detailDueDate').innerText = task.due_date || '—';

        if (task.created_at) {
            let date = new Date(task.created_at);
            document.getElementById('detailCreated').innerText = date.toLocaleDateString('en-US', {
                month: 'short',
                day: 'numeric',
                year: 'numeric'
            });
        }

        let assigneesContainer = document.getElementById('detailAssignees');
        if (assigneesContainer) {
            if (task.assignees && task.assignees.length > 0) {
                assigneesContainer.innerHTML = task.assignees.map(user => `<div class="inline-flex items-center gap-1.5 bg-[#080D16] border border-gray-800 px-2.5 py-1 rounded text-[11px] text-gray-300"><span>${user.name}</span></div>`).join('');
            } else {
                assigneesContainer.innerHTML = '<span class="text-[11px] text-gray-500">Unassigned</span>';
            }
        }

        let attachContainer = document.getElementById('detailAttachments');
        if (attachContainer) {
            if (task.attachments && task.attachments.length > 0) {
                let files = typeof task.attachments === 'string' ? JSON.parse(task.attachments) : task.attachments;
                let fileHtml = files.map(f => {
                    let path = typeof f === 'object' ? f.file_path : f;
                    let name = (typeof f === 'object' && f.original_name) ? f.original_name : path.split('/').pop();

                    let isPdf = name.toLowerCase().endsWith('.pdf');
                    let icon = isPdf ? '📄' : '📎';

                    return `<div class="mt-1"><a href="/storage/${path}" target="_blank" rel="noopener noreferrer" class="text-[#00B8D9] hover:underline inline-flex items-center gap-1">${icon} ${name}</a></div>`;
                }).join('');
                attachContainer.innerHTML = fileHtml;
            } else {
                attachContainer.innerText = 'No attachments.';
            }
        }

        renderSubtasks(task.subtasks || []);
        renderComments(task.comments || []);

        document.getElementById('taskDetailModal').classList.remove('hidden');

    } catch (error) {
        console.error("Error loading task details:", error);
    }
}

function closeTaskDetailModal() {
    document.getElementById('taskDetailModal').classList.add('hidden');
}

// Toggle Subtask Form Input //
function toggleSubtaskForm() {
    let form = document.getElementById('subtaskForm');
    if (form) {
        form.classList.toggle('hidden');
        if (!form.classList.contains('hidden')) {
            document.getElementById('newSubtaskTitle').focus();
        }
    }
}

// Render Subtasks List //
function renderSubtasks(subtasks) {
    let container = document.getElementById('detailSubtasks');
    if (!container) return;

    if (!subtasks || subtasks.length === 0) {
        container.innerHTML = '<div class="text-xs text-gray-500 py-2">No subtasks</div>';
        updateSubtaskStats();
        return;
    }

    container.innerHTML = subtasks.map(st => `
            <div class="subtask-item flex items-center justify-between bg-[#16202E] p-2.5 rounded-lg border border-gray-800 mb-2" data-subtask-id="${st.id}">
                <div class="flex items-center gap-2.5">
                    <input type="checkbox"
                           class="w-4 h-4 rounded border-gray-700 bg-gray-900 text-[#00B8D9] focus:ring-0 cursor-pointer"
                           ${st.is_completed ? 'checked' : ''}
                           onchange="toggleSubtask(${st.id}, this)">
                    <span class="text-xs ${st.is_completed ? 'line-through text-gray-500' : 'text-gray-200'}">
                        ${st.title}
                    </span>
                </div>
                <button type="button"
                        onclick="deleteSubtask(${st.id}, this)"
                        class="text-gray-500 hover:text-red-400 p-1 transition cursor-pointer"
                        title="Delete Subtask">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
        `).join('');

    updateSubtaskStats();
}

// Save Subtask Function //
function saveSubtask() {
    const titleInput = document.getElementById('newSubtaskTitle');
    const title = titleInput ? titleInput.value.trim() : '';

    if (!title) {
        alert('Subtask title is required');
        return;
    }

    if (!currentTaskId) {
        alert('Missing Task ID');
        return;
    }

    fetch('/user/subtasks', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            task_id: currentTaskId,
            title: title
        })
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                titleInput.value = '';
                toggleSubtaskForm();

                const container = document.getElementById('detailSubtasks');
                if (container) {
                    if (container.innerText.includes('No subtasks')) {
                        container.innerHTML = '';
                    }

                    const subtaskHtml = `
                        <div class="subtask-item flex items-center justify-between bg-[#16202E] p-2.5 rounded-lg border border-gray-800 mb-2" data-subtask-id="${data.subtask.id}">
                            <div class="flex items-center gap-2.5">
                                <input type="checkbox"
                                       class="w-4 h-4 rounded border-gray-700 bg-gray-900 text-[#00B8D9] focus:ring-0 cursor-pointer"
                                       onchange="toggleSubtask(${data.subtask.id}, this)">
                                <span class="text-xs text-gray-200">${data.subtask.title}</span>
                            </div>
                            <button type="button"
                                    onclick="deleteSubtask(${data.subtask.id}, this)"
                                    class="text-gray-500 hover:text-red-400 p-1 transition cursor-pointer"
                                    title="Delete Subtask">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>`;

                    container.insertAdjacentHTML('beforeend', subtaskHtml);
                    updateSubtaskStats();
                }

                const taskCard = document.querySelector(`[data-task-id="${currentTaskId}"]`);
                if (taskCard) {
                    let taskData = JSON.parse(taskCard.getAttribute('data-task'));
                    if (!taskData.subtasks) taskData.subtasks = [];
                    taskData.subtasks.push(data.subtask);
                    taskCard.setAttribute('data-task', JSON.stringify(taskData));

                    const cardSubtaskContainer = document.getElementById(`card-subtasks-${currentTaskId}`);
                    if (cardSubtaskContainer) {
                        cardSubtaskContainer.innerHTML = `<span class="text-[11px] text-[#00B8D9] font-medium">${taskData.subtasks.length} Subtask(s)</span>`;
                    }
                }

            } else {
                alert('Error: ' + (data.error || 'Subtask can not be saved'));
            }
        })
        .catch(err => {
            console.error("Error:", err);
            alert('Server Error!');
        });
}

// Toggle Subtask Completion in Database //
function toggleSubtask(subtaskId, checkbox) {
    const isCompleted = checkbox.checked;
    const titleSpan = checkbox.nextElementSibling;

    fetch(`/user/subtasks/${subtaskId}/toggle`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
        },
        body: JSON.stringify({is_completed: isCompleted})
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                if (titleSpan) {
                    if (isCompleted) {
                        titleSpan.classList.add('line-through', 'text-gray-500');
                        titleSpan.classList.remove('text-gray-200');
                    } else {
                        titleSpan.classList.remove('line-through', 'text-gray-500');
                        titleSpan.classList.add('text-gray-200');
                    }
                }
                updateSubtaskStats();
            } else {
                checkbox.checked = !isCompleted;
            }
        })
        .catch(err => {
            console.error('Error toggling subtask:', err);
            checkbox.checked = !isCompleted;
        });
}

// Delete Subtask //
function deleteSubtask(subtaskId, btnElement) {
    if (!confirm('Are you sure you want to delete this subtask?')) return;

    fetch(`/user/subtasks/${subtaskId}`, {
        method: 'DELETE',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
        }
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const subtaskRow = btnElement.closest('.subtask-item');
                if (subtaskRow) subtaskRow.remove();

                const subtasksList = document.getElementById('detailSubtasks');
                if (subtasksList && subtasksList.querySelectorAll('.subtask-item').length === 0) {
                    subtasksList.innerHTML = '<div class="text-xs text-gray-500 py-2">No subtasks</div>';
                }
                updateSubtaskStats();
            } else {
                alert('Error deleting subtask');
            }
        })
        .catch(err => console.error('Error deleting subtask:', err));
}

function renderComments(comments) {
    let container = document.getElementById('detailComments');
    if (!comments || comments.length === 0) {
        container.innerHTML = '<span class="text-gray-500 block">No comments yet.</span>';
        return;
    }

    container.innerHTML = comments.map(c => `
        <div class="bg-[#03060B] border border-gray-800 p-2.5 rounded-lg text-xs text-gray-300 mb-2">
            <div class="flex justify-between items-center mb-1 text-[10px] text-gray-500">
                <span class="font-semibold text-gray-400">${c.user ? c.user.name : 'User'}</span>
                <span>${c.created_at ? new Date(c.created_at).toLocaleTimeString([], {
        hour: '2-digit',
        minute: '2-digit'
    }) : ''}</span>
            </div>
            <p class="text-gray-300">${c.comment}</p>
        </div>
    `).join('');
}

function saveComment() {
    const commentInput = document.getElementById('newCommentText');
    const comment = commentInput ? commentInput.value.trim() : '';

    if (!comment) {
        alert('Comment required');
        return;
    }

    if (!currentTaskId) {
        alert('Task ID missing hai');
        return;
    }

    fetch('/user/comments', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            task_id: currentTaskId,
            comment: comment
        })
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                commentInput.value = '';

                const container = document.getElementById('detailComments');
                if (container.innerText.includes('No comments yet.')) {
                    container.innerHTML = '';
                }

                container.innerHTML += `
                <div class="bg-[#03060B] border border-gray-800 p-2.5 rounded-lg text-xs text-gray-300 mb-2">
                    <div class="flex justify-between items-center mb-1 text-[10px] text-gray-500">
                        <span class="font-semibold text-gray-400">${data.comment.user ? data.comment.user.name : 'You'}</span>
                        <span>Just now</span>
                    </div>
                    <p class="text-gray-300">${data.comment.comment || comment}</p>
                </div>
            `;
                const taskCard = document.querySelector(`[data-task-id="${currentTaskId}"]`);
                if (taskCard) {
                    let taskData = JSON.parse(taskCard.getAttribute('data-task'));
                    if (!taskData.comments) taskData.comments = [];
                    taskData.comments.push(data.comment);
                    taskCard.setAttribute('data-task', JSON.stringify(taskData));
                }
            } else {
                alert('Error: ' + (data.error || 'Comment not saved'));
            }
        })
        .catch(err => {
            console.error("Error:", err);
            alert('Server Error!');
        });
}

function deleteTask() {
    if (!currentTaskId) {
        alert('Task ID is missing!');
        return;
    }

    if (!confirm('Do you want to delete this task?')) {
        return;
    }

    fetch(`/user/tasks/${currentTaskId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
        }
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                closeTaskDetailModal();
                location.reload();
            } else {
                alert('Error: ' + (data.error || 'Task not delete'));
            }
        })
        .catch(err => {
            console.error("Error:", err);
            alert('Server Error!');
        });
}

function openEditTaskModal(task) {
    if (!task) return;

    document.getElementById('editTaskId').value = task.id;
    document.getElementById('editSummary').value = task.summary || '';
    document.getElementById('editDescription').value = task.description || '';

    if (document.getElementById('editProject')) {
        document.getElementById('editProject').value = task.project_id || '';
    }
    if (document.getElementById('editStatus')) {
        document.getElementById('editStatus').value = task.task_status_id || '';
    }
    if (document.getElementById('editPriority')) {
        document.getElementById('editPriority').value = task.priority || 'Medium';
    }
    if (document.getElementById('editDueDate')) {
        document.getElementById('editDueDate').value = task.due_date || '';
    }

    let assigneesSelect = document.getElementById('editAssignees');
    if (assigneesSelect) {
        let assignedIds = task.assignees ? task.assignees.map(u => u.id) : [];
        Array.from(assigneesSelect.options).forEach(option => {
            option.selected = assignedIds.includes(parseInt(option.value));
        });
    }

    let fileInput = document.getElementById('editAttachments');
    if (fileInput) fileInput.value = '';

    document.getElementById('editTaskModal').classList.remove('hidden');
}

function openEditModalFromCard(btn) {
    const card = btn.closest('[data-task]');
    if (!card) return;

    try {
        let task = JSON.parse(card.getAttribute('data-task'));
        openEditTaskModal(task);
    } catch (e) {
        console.error("Task data parse error:", e);
    }
}

function closeEditTaskModal() {
    document.getElementById('editTaskModal').classList.add('hidden');
}

function closeEditModal() {
    closeEditTaskModal();
}

function submitEditTask(e) {
    e.preventDefault();
    const taskId = document.getElementById('editTaskId').value;
    let formData = new FormData();

    formData.append('_method', 'PUT');

    formData.append('summary', document.getElementById('editSummary').value);
    formData.append('description', document.getElementById('editDescription').value);

    if (document.getElementById('editProject')) {
        formData.append('project_id', document.getElementById('editProject').value);
    }
    if (document.getElementById('editStatus')) {
        formData.append('task_status_id', document.getElementById('editStatus').value);
    }
    if (document.getElementById('editPriority')) {
        formData.append('priority', document.getElementById('editPriority').value);
    }
    if (document.getElementById('editDueDate')) {
        formData.append('due_date', document.getElementById('editDueDate').value);
    }

    let assigneesSelect = document.getElementById('editAssignees');
    if (assigneesSelect) {
        let selectedAssignees = Array.from(assigneesSelect.selectedOptions).map(o => o.value);
        selectedAssignees.forEach(id => formData.append('assignees[]', id));
    }

    let fileInput = document.getElementById('editAttachments');
    if (fileInput && fileInput.files.length > 0) {
        for (let i = 0; i < fileInput.files.length; i++) {
            formData.append('attachments[]', fileInput.files[i]);
        }
    }

    fetch(`/user/tasks/${taskId}`, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
        },
        body: formData
    })
        .then(res => res.json())
        .then(resData => {
            if (resData.success) {
                closeEditTaskModal();
                location.reload();
            } else {
                alert('Error updating task: ' + (resData.error || 'Unknown error'));
            }
        })
        .catch(err => {
            console.error("Error:", err);
            alert('Server Error!');
        });
}

function addSubtask() {
    saveSubtask();
}

function extracted() {
    saveSubtask();
}

document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.kanban-column').forEach(column => {
        new Sortable(column, {
            group: 'kanban',
            animation: 150,
            draggable: '.task-card',
            ghostClass: 'opacity-50',
            dragClass: 'shadow-2xl',
            onEnd: function (evt) {
                const itemEl = evt.item;
                const taskId = itemEl.getAttribute('data-task-id');
                const newColumn = evt.to;
                const oldColumn = evt.from;
                const newStatusId = newColumn.getAttribute('data-status-id');
                const oldStatusId = oldColumn.getAttribute('data-status-id');

                if (newStatusId === oldStatusId) return;

                updateColumnState(oldColumn);
                updateColumnState(newColumn);

                fetch(window.routes.updateStatus, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.csrfToken || document.querySelector('meta[name="csrf-token"]')?.content
                    },
                    body: JSON.stringify({
                        task_id: taskId,
                        status_id: newStatusId
                    })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            let rawTask = itemEl.getAttribute('data-task');
                            if (rawTask) {
                                let taskData = JSON.parse(rawTask);

                                taskData.task_status_id = newStatusId;
                                taskData.status = {
                                    id: newStatusId,
                                    name: data.status_name || (data.status ? data.status.name : ''),
                                    color: data.status_color || (data.status ? data.status.color : '#3B82F6')
                                };

                                itemEl.setAttribute('data-task', JSON.stringify(taskData));
                            }
                        } else {
                            oldColumn.appendChild(itemEl);
                            updateColumnState(oldColumn);
                            updateColumnState(newColumn);
                        }
                    })
                    .catch(err => {
                        console.error('Error:', err);
                        oldColumn.appendChild(itemEl);
                        updateColumnState(oldColumn);
                        updateColumnState(newColumn);
                    });
            }
        });
    });

    function updateColumnState(column) {
        if (!column) return;

        const cards = column.querySelectorAll('.task-card');

        const placeholder = column.querySelector('.no-tasks-placeholder');
        if (placeholder) {
            if (cards.length === 0) {
                placeholder.classList.remove('hidden');
            } else {
                placeholder.classList.add('hidden');
            }
        }

        const columnHeaderBlock = column.parentElement || column.closest('div');
        if (columnHeaderBlock) {
            const badge = columnHeaderBlock.querySelector('.task-count');
            if (badge) {
                badge.innerText = cards.length;
            }
        }
    }

});

// Subtasks Stats Dynamic Counter //
function updateSubtaskStats() {
    const checkboxes = document.querySelectorAll('.subtask-item input[type="checkbox"]');

    const total = checkboxes.length;
    let completed = 0;

    checkboxes.forEach(cb => {
        if (cb.checked) {
            completed++;
        }
    });

    const remaining = total - completed;
    const percentage = total > 0 ? Math.round((completed / total) * 100) : 0;

    const progressText = document.getElementById('stat-progress-text');
    const totalEl = document.getElementById('stat-total');
    const completedEl = document.getElementById('stat-completed');
    const remainingEl = document.getElementById('stat-remaining');

    if (progressText) {
        progressText.innerText = total > 0
            ? `${completed} of ${total} subtasks · ${percentage}%`
            : 'No subtasks · 0%';
    }
    if (totalEl) totalEl.innerText = total;
    if (completedEl) completedEl.innerText = completed;
    if (remainingEl) remainingEl.innerText = remaining;
}

document.addEventListener('change', function (e) {
    if (e.target && e.target.matches('.subtask-item input[type="checkbox"]')) {
        updateSubtaskStats();
    }
});


document.addEventListener('DOMContentLoaded', () => {
    /** @type {HTMLElement} */
    const container = document.querySelector('#kanbanBoard') || document.querySelector('.kanban-container');

    if (!container) return;

    let isDown = false;
    let startX;
    let scrollLeft;

    container.addEventListener('mousedown', (e) => {
        if (e.target.closest('button, a, input, select, textarea, .task-card')) return;

        isDown = true;
        container.style.cursor = 'grabbing';
        container.style.userSelect = 'none';
        startX = e.pageX - container.offsetLeft;
        scrollLeft = container.scrollLeft;
    });

    container.addEventListener('mouseleave', () => {
        isDown = false;
        container.style.cursor = 'default';
    });

    container.addEventListener('mouseup', () => {
        isDown = false;
        container.style.cursor = 'default';
    });

    container.addEventListener('mousemove', (e) => {
        if (!isDown) return;
        e.preventDefault();
        const x = e.pageX - container.offsetLeft;
        const walk = (x - startX) * 1.5;
        container.scrollLeft = scrollLeft - walk;
    });
});


// Dropdown Open / Close Toggle //
function toggleAssigneeDropdown() {
    const menu = document.getElementById('assigneesDropdownMenu');
    if (menu) menu.classList.toggle('hidden');
}

function updateAssigneeSelection() {
    const checkboxes = document.querySelectorAll('.assignee-checkbox:checked');
    const select = document.getElementById('editAssignees');
    const textSpan = document.getElementById('selectedAssigneesText');

    if (!select || !textSpan) return;

    Array.from(select.options).forEach(opt => opt.selected = false);

    let selectedNames = [];
    checkboxes.forEach(cb => {
        selectedNames.push(cb.getAttribute('data-name'));
        const option = select.querySelector(`option[value="${cb.value}"]`);
        if (option) option.selected = true;
    });

    if (selectedNames.length > 0) {
        textSpan.textContent = selectedNames.join(', ');
        textSpan.classList.remove('text-gray-400');
        textSpan.classList.add('text-white');
    } else {
        textSpan.textContent = 'Select Assignees...';
        textSpan.classList.add('text-gray-400');
        textSpan.classList.remove('text-white');
    }
}

document.addEventListener('click', (e) => {
    const btn = document.getElementById('assigneesDropdownBtn');
    const menu = document.getElementById('assigneesDropdownMenu');
    if (btn && menu && !btn.contains(e.target) && !menu.contains(e.target)) {
        menu.classList.add('hidden');
    }
});

function setEditAssignees(assignedUserIds = []) {
    document.querySelectorAll('.assignee-checkbox').forEach(cb => {
        cb.checked = assignedUserIds.includes(parseInt(cb.value)) || assignedUserIds.includes(cb.value.toString());
    });
    updateAssigneeSelection();
}

// Toggle Create Assignee Dropdown
function toggleCreateAssigneeDropdown() {
    const menu = document.getElementById('createAssigneesDropdownMenu');
    if (menu) menu.classList.toggle('hidden');
}

function updateCreateAssigneeSelection() {
    const checkboxes = document.querySelectorAll('.create-assignee-checkbox:checked');
    const select = document.getElementById('createAssignees');
    const textSpan = document.getElementById('createSelectedAssigneesText');

    if (!select || !textSpan) return;

    Array.from(select.options).forEach(opt => opt.selected = false);

    let selectedNames = [];
    checkboxes.forEach(cb => {
        selectedNames.push(cb.getAttribute('data-name'));
        const option = select.querySelector(`option[value="${cb.value}"]`);
        if (option) option.selected = true;
    });

    if (selectedNames.length > 0) {
        textSpan.textContent = selectedNames.join(', ');
        textSpan.classList.remove('text-gray-400');
        textSpan.classList.add('text-white');
    } else {
        textSpan.textContent = 'Select Assignees...';
        textSpan.classList.add('text-gray-400');
        textSpan.classList.remove('text-white');
    }
}

document.addEventListener('click', (e) => {
    const createBtn = document.getElementById('createAssigneesDropdownBtn');
    const createMenu = document.getElementById('createAssigneesDropdownMenu');
    if (createBtn && createMenu && !createBtn.contains(e.target) && !createMenu.contains(e.target)) {
        createMenu.classList.add('hidden');
    }
});