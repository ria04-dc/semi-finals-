<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Task Manager</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #f7f3f3;
            font-family: 'Georgia', 'Times New Roman', serif;
            color: #1a1a1a;
            padding: 40px;
        }

        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Top Header - Centered Title */
        .title-container {
            display: flex;
            justify-content: center;
            margin-bottom: 25px;
        }

        .header-banner {
            background-color: #b82525;
            color: #ffffff;
            padding: 22px 50px;
            border-radius: 24px;
            font-size: 38px;
            font-weight: 500;
            letter-spacing: -0.5px;
            box-shadow: 0 4px 15px rgba(184, 37, 37, 0.25);
            text-align: center;
            width: 100%;
            max-width: 650px;
        }

        /* Controls Bar: Search on left, Add Task on right */
        .controls-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .search-box {
            flex-shrink: 0;
        }

        .search-input {
            background-color: #ffffff;
            border: 2px solid #b82525;
            border-radius: 30px;
            padding: 12px 24px;
            font-size: 16px;
            font-family: inherit;
            color: #2b2b2b;
            outline: none;
            width: 250px;
            box-shadow: 2px 3px 0px #b82525;
        }

        .btn-add-task {
            background-color: #b82525;
            color: #ffffff;
            text-decoration: none;
            padding: 10px 24px;
            border-radius: 12px;
            font-size: 18px;
            font-weight: 500;
            display: inline-block;
            transition: all 0.2s ease;
            box-shadow: 0 4px 10px rgba(184, 37, 37, 0.2);
            border: 2px solid #801818;
        }

        .btn-add-task:hover {
            background-color: #9e1f1f;
            transform: translateY(-2px);
        }

        /* Section Title under search task */
        .section-title {
            font-size: 30px;
            font-weight: 500;
            color: #1a1a1a;
            margin-bottom: 20px;
        }

        /* Task Flow Cards Row */
        .cards-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
            margin-bottom: 40px;
        }

        .stat-card {
            background-color: #b82525;
            border: 3px solid #f7f3f3;
            border-radius: 20px;
            height: 130px;
            padding: 20px 25px;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            box-shadow: 0 6px 18px rgba(184, 37, 37, 0.2);
        }

        .stat-card .label {
            font-size: 18px;
            opacity: 0.95;
            margin-bottom: 6px;
        }

        .stat-card .number {
            font-size: 36px;
            font-weight: bold;
        }

        /* Alert Notification */
        .alert-success {
            background-color: #ffffff;
            color: #b82525;
            border: 2px solid #b82525;
            padding: 12px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 16px;
        }

        /* Table Layout */
        .task-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 8px 8px;
        }

        .task-table th {
            background-color: #2b2b2b;
            color: #ffffff;
            font-size: 20px;
            font-weight: normal;
            padding: 16px 20px;
            text-align: left;
            border-radius: 8px;
            border: 1px solid #404040;
        }

        .task-table td {
            background-color: #ffffff;
            border: 2px solid #2b2b2b;
            color: #1a1a1a;
            font-size: 16px;
            padding: 16px 20px;
            border-radius: 8px;
        }

        .action-btns {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .btn-action {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 14px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-family: inherit;
        }

        .btn-toggle {
            background-color: #2b2b2b;
            color: white;
        }

        .btn-edit {
            background-color: #ffffff;
            color: #b82525;
            border: 1px solid #b82525;
        }

        .btn-delete {
            background-color: #b82525;
            color: white;
        }

        .badge-pending {
            color: #d97706;
            font-weight: bold;
        }

        .badge-completed {
            color: #16a34a;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="dashboard-container">

    <!-- 1. Centered Header Banner -->
    <div class="title-container">
        <div class="header-banner">
            Personal Task Manager
        </div>
    </div>

    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- 2. Search Task & Add Task Button Bar -->
    <div class="controls-row">
        <div class="search-box">
            <input type="text" id="searchInput" class="search-input" placeholder="Search task..." onkeyup="filterTasks()">
        </div>
        <a href="{{ route('tasks.create') }}" class="btn-add-task">+ Add Task</a>
    </div>

    <!-- 3. Section Title directly under Search Task -->
    <h2 class="section-title">My task flow</h2>

    <!-- 4. Summary Statistics Cards -->
    <div class="cards-row">
        <div class="stat-card">
            <div class="label">Total Tasks</div>
            <div class="number">{{ $tasks->count() }}</div>
        </div>
        <div class="stat-card">
            <div class="label">Pending Tasks</div>
            <div class="number">{{ $tasks->where('status', 'Pending')->count() }}</div>
        </div>
        <div class="stat-card">
            <div class="label">Completed Tasks</div>
            <div class="number">{{ $tasks->where('status', 'Completed')->count() }}</div>
        </div>
    </div>

    <!-- 5. Task Data Table -->
    <table class="task-table" id="tasksTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Task</th>
                <th>Description</th>
                <th>Status</th>
                <th>Due Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tasks as $task)
                <tr>
                    <td>{{ $task->id }}</td>
                    <td><strong>{{ $task->task_name }}</strong></td>
                    <td>{{ $task->description ?? '-' }}</td>
                    <td>
                        <span class="{{ $task->status === 'Pending' ? 'badge-pending' : 'badge-completed' }}">
                            {{ $task->status }}
                        </span>
                    </td>
                    <td>{{ $task->due_date ?? '-' }}</td>
                    <td>
                        <div class="action-btns">
                            <form action="{{ route('tasks.updateStatus', $task->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn-action btn-toggle" title="Toggle Status">Status</button>
                            </form>

                            <a href="{{ route('tasks.edit', $task->id) }}" class="btn-action btn-edit">Edit</a>

                            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this task?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #666; padding: 25px;">
                        No tasks created yet. Click "+ Add Task" to get started!
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>

<script>
    function filterTasks() {
        let input = document.getElementById('searchInput').value.toLowerCase();
        let rows = document.querySelectorAll('#tasksTable tbody tr');

        rows.forEach(row => {
            let text = row.innerText.toLowerCase();
            row.style.display = text.includes(input) ? '' : 'none';
        });
    }
</script>

</body>
</html>