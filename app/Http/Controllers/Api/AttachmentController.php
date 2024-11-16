<?php

namespace App\Http\Controllers\Api;

use App\Models\Attachment;
use Illuminate\Http\Request;
use App\Traits\Api\ResponseTrait;
use App\Services\Task\ITaskService;
use App\Http\Controllers\Controller;
use App\Services\Attachment\AttachmentService;
use App\Http\Resources\Attachment\AttachmentResource;
use App\Http\Requests\Api\Attachment\ListAttachmentsRequest;
use App\Http\Requests\Api\Attachment\StoreAttachmentRequest;

class AttachmentController extends Controller
{
    use ResponseTrait;

    public function __construct(private AttachmentService $attachmentService, private ITaskService $taskService)
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index(ListAttachmentsRequest $request, $taskId)
    {
        $task = $this->taskService->getById($taskId);

        if (!$task) {
            return $this->errorResponse('task not found');
        }

        $attachments = $task->attachments()
            ->when($request->type, fn($query) => $query->whereFileType($request->type))
            ->orderBy('uploaded_at', 'desc')
            ->paginate($request->per_page ?? 20);

        return $this->successResponse(
            [
                'attachments' => AttachmentResource::collection($attachments),
                'meta' => [
                    'pagination' => [
                        'total' => $attachments->total(),
                        'per_page' => $attachments->perPage(),
                        'current_page' => $attachments->currentPage(),
                        'last_page' => $attachments->lastPage(),
                    ]
                ]
            ],
            'attachments retreived successfully',
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAttachmentRequest $request, $taskId)
    {
        $task = $this->taskService->getById($taskId);

        if (!$task) {
            return $this->errorResponse('task not found');
        }

        $attachments = collect($request->file('files'))
            ->map(fn($file) => $this->attachmentService->storeAttachment($task, $file));

        return $this->successResponse(
            AttachmentResource::collection($attachments),
            'attachments stored successfully',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function download($attachmentId)
    {
        $attachment = Attachment::find($attachmentId);
        return $this->attachmentService->downloadAttachment($attachment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($attachmentId)
    {
        $attachment = Attachment::find($attachmentId);
        $this->attachmentService->deleteAttachment($attachment);

        return $this->successResponse([], 'attachment deleted successfully');
    }
}
