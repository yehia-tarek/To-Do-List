<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Attachment;
use Illuminate\Auth\Access\HandlesAuthorization;

class AttachmentPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    use HandlesAuthorization;

    public function view(User $user, Attachment $attachment): bool
    {
        return $user->can('view', $attachment->task);
    }

    public function delete(User $user, Attachment $attachment): bool
    {
        return $user->can('update', $attachment->task);
    }
}
