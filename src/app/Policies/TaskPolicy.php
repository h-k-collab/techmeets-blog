<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    // 完了・未完了の切り替え(更新)は、持ち主だけ
    public function update(User $user, Task $task): bool
    {
        return $user->id === $task->user_id;
    }

    // 削除は、持ち主だけ
    public function delete(User $user, Task $task): bool
    {
        return $user->id === $task->user_id;
    }
}