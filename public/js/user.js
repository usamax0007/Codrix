function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');

    if (sidebar.classList.contains('hidden')) {
        sidebar.classList.remove('hidden');
        overlay.classList.remove('hidden');
    } else {
        sidebar.classList.add('hidden');
        overlay.classList.add('hidden');
    }
}


const cards = document.querySelectorAll('.task-card');
const columns = document.querySelectorAll('.status-column');
let draggedCard = null;
let clone = null;
let offsetX = 0;
let offsetY = 0;

function moveTask(card, column) {
    const taskList = column.querySelector('.task-list');
    const taskId = card.dataset.taskId;
    const oldStatus = card.dataset.currentStatus;
    const newStatus = column.dataset.status;
    if (oldStatus === newStatus) {
        return;
    }
    taskList.appendChild(card);
    card.dataset.currentStatus = newStatus;
    updateCounters();
    updateTaskStatus(taskId, newStatus);
}

function highlightColumn(column) {
    const list = column.querySelector('.task-list');
    list.style.backgroundColor = 'rgba(16, 185, 129, 0.1)';
    list.style.border = '2px dashed #10b981';
}

function clearHighlight() {

    columns.forEach(column => {
        const list = column.querySelector('.task-list');
        list.style.backgroundColor = '';
        list.style.border = '';
    });
}

function getColumn(x, y) {
    return [...columns].find(column => {
        const rect = column.getBoundingClientRect();
        return (
            x >= rect.left &&
            x <= rect.right &&
            y >= rect.top &&
            y <= rect.bottom
        );
    });
}

cards.forEach(card => {
    card.addEventListener('dragstart', function(e) {
        draggedCard = this;
        this.style.opacity = '0.4';
        this.style.transform = 'scale(0.95) rotate(2deg)';
        e.dataTransfer.effectAllowed = 'move';
    });

    card.addEventListener('dragend', function() {
        this.style.opacity = '';
        this.style.transform = '';
        draggedCard = null;
        clearHighlight();
    });

    card.addEventListener('touchstart', function(e) {
        const touch = e.touches[0];
        const rect = this.getBoundingClientRect();
        draggedCard = this;
        offsetX = touch.clientX - rect.left;
        offsetY = touch.clientY - rect.top;
        clone = this.cloneNode(true);
        clone.style.position = 'fixed';
        clone.style.width = rect.width + 'px';
        clone.style.height = rect.height + 'px';
        clone.style.left = rect.left + 'px';
        clone.style.top = rect.top + 'px';
        clone.style.zIndex = '9999';
        clone.style.opacity = '0.8';
        clone.style.transform = 'scale(1.05)';
        clone.style.pointerEvents = 'none';
        clone.style.boxShadow = '0 10px 40px rgba(0,0,0,0.4)';
        document.body.appendChild(clone);
        this.style.opacity = '0.3';
    }, { passive: false });

    card.addEventListener('touchmove', function(e) {
        if (!clone) return;
        e.preventDefault();
        const touch = e.touches[0];
        clone.style.left =
            (touch.clientX - offsetX) + 'px';
        clone.style.top =
            (touch.clientY - offsetY) + 'px';
        clearHighlight();
        const column = getColumn(
            touch.clientX,
            touch.clientY
        );

        if (column) {
            highlightColumn(column);
        }
    }, { passive: false });

    card.addEventListener('touchend', function(e) {
        if (!clone) return;
        const touch = e.changedTouches[0];
        const column = getColumn(
            touch.clientX,
            touch.clientY
        );

        if (column) {
            moveTask(draggedCard, column);
        }
        finishTouchDrag();
    });

    card.addEventListener('touchcancel', function() {
        finishTouchDrag();
    });
});

columns.forEach(column => {
    column.addEventListener('dragover', function(e) {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';
        highlightColumn(this);
    });

    column.addEventListener('dragleave', function(e) {
        if (!this.contains(e.relatedTarget)) {
            clearHighlight();
        }
    });

    column.addEventListener('drop', function(e) {
        e.preventDefault();
        clearHighlight();
        if (draggedCard) {
            moveTask(draggedCard, this);
        }
    });
});

function finishTouchDrag() {
    if (draggedCard) {
        draggedCard.style.opacity = '';
    }
    if (clone) {
        clone.remove();
    }
    clone = null;
    draggedCard = null;
    clearHighlight();
}

function updateCounters() {
    columns.forEach(column => {
        const count =
            column.querySelectorAll('.task-card').length;
        column.querySelector('.task-count').textContent = count;
    });
}

function updateTaskStatus(taskId, newStatus) {
    const csrfToken =
        document.querySelector(
            'meta[name="csrf-token"]'
        ).content;
    const route = window.taskStatusUpdateRoute.replace(':id', taskId);
    fetch(
        route,
        {
            method: 'PUT',

            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },

            body: JSON.stringify({
                status: newStatus
            })
        }
    )
        .then(response => response.json())
        .then(data => {

            if (data.success) {
                console.log('Task status updated successfully');
            }

        })
        .catch(error => {

            console.error(
                'Error updating task status:',
                error
            );

        });
}


// mouse scroller

const board = document.getElementById('task-board');

let isDown = false;
let startX;
let scrollLeft;

board.addEventListener('mousedown', (e) => {
    if (e.target.closest('.task-card')) return;

    isDown = true;
    board.classList.add('cursor-grabbing');

    startX = e.pageX - board.offsetLeft;
    scrollLeft = board.scrollLeft;
});

board.addEventListener('mouseleave', () => {
    isDown = false;
    board.classList.remove('cursor-grabbing');
});

board.addEventListener('mouseup', () => {
    isDown = false;
    board.classList.remove('cursor-grabbing');
});

board.addEventListener('mousemove', (e) => {
    if (!isDown) return;

    e.preventDefault();
    const x = e.pageX - board.offsetLeft;
    const walk = (x - startX) * 1.5;

    board.scrollLeft = scrollLeft - walk;
});


