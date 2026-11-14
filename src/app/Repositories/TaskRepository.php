<?php

namespace App\Repositories;

use App\Models\Task;

class TaskRepository
{
    // ① ユーザーのタスクを一覧で取得(未完了を先に、期限が近い順)
    public function getByUser(int $userId)
    {
        return Task::where('user_id', $userId)
            ->orderBy('is_completed')
            ->orderBy('due_date')
            ->get();
    }

    // 1件取得(なければ404)
    public function findById(int $id)
    {
        return Task::findOrFail($id);
    }

    // ② 保存
    public function create(array $data)
    {
        return Task::create($data);
    }

    // ③ 更新(完了・未完了の切り替えも、これで行う)
    public function update(Task $task, array $data)
    {
        $task->update($data);
        return $task;
    }

    // ④ 削除
    public function delete(Task $task)
    {
        return $task->delete();
    }
}