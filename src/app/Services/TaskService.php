<?php

namespace App\Services;

use App\Models\Task;
use App\Repositories\TaskRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TaskService
{
    public function __construct(
        private TaskRepository $taskRepository
    ) {}

    // 追加
    public function createTask(array $data)
    {
        return DB::transaction(function () use ($data) {
            $task = $this->taskRepository->create($data);

            Log::info('Task created', ['task_id' => $task->id]);

            return $task;
        });
    }

    // 完了・未完了の切り替え
    public function toggleCompletion(Task $task)
    {
        return DB::transaction(function () use ($task) {
            $updated = $this->taskRepository->update($task, [
                'is_completed' => ! $task->is_completed,
            ]);

            Log::info('Task toggled', [
                'task_id'      => $updated->id,
                'is_completed' => $updated->is_completed,
            ]);

            return $updated;
        });
    }

    // 削除
    public function deleteTask(Task $task)
    {
        return DB::transaction(function () use ($task) {
            $this->taskRepository->delete($task);

            Log::info('Task deleted', ['task_id' => $task->id]);
        });
    }
}