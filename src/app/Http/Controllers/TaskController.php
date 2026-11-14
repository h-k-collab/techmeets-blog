<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Repositories\TaskRepository;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    public function __construct(
        private TaskService $taskService,
        private TaskRepository $taskRepository
    ) {}

    // 一覧(自分のタスクだけ)
    public function index()
    {
        $tasks = $this->taskRepository->getByUser(auth()->id());
        return view('tasks.index', compact('tasks'));
    }

    // 追加
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'    => 'required|max:100',
            'due_date' => 'nullable|date',
        ]);

        $validated['user_id'] = auth()->id();

        $this->taskService->createTask($validated);

        return redirect()->route('tasks.index')
            ->with('success', 'タスクを追加しました');
    }

    // 完了・未完了の切り替え
    public function toggle(Task $task)
    {
        Gate::authorize('update', $task);

        $this->taskService->toggleCompletion($task);

        return redirect()->route('tasks.index');
    }

    // 削除
    public function destroy(Task $task)
    {
        Gate::authorize('delete', $task);

        $this->taskService->deleteTask($task);

        return redirect()->route('tasks.index')
            ->with('success', 'タスクを削除しました');
    }
}