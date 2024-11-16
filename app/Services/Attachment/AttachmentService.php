<?php

namespace App\Services\Attachment;

use App\Models\Task;
use App\Models\Attachment;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AttachmentService
{
    private const ATTACHMENTS_DISK = 'attachments';

    public function storeAttachment(Task $task, UploadedFile $file): Attachment
    {
        $fileName = $this->generateFileName($file);
        $filePath = $this->generateFilePath($task->id, $fileName);

        // Store file
        Storage::disk(self::ATTACHMENTS_DISK)->putFileAs(
            dirname($filePath),
            $file,
            basename($filePath)
        );

        // Create attachment record
        return $task->attachments()->create([
            'file_name' => $file->getClientOriginalName(),
            'file_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'file_url' => $filePath,
            'uploaded_at' => now(),
        ]);
    }

    public function downloadAttachment(Attachment $attachment): StreamedResponse
    {
        $disk = Storage::disk(self::ATTACHMENTS_DISK);

        if (!$disk->exists($attachment->file_url)) {
            abort(404, 'File not found');
        }

        return $disk->download(
            $attachment->file_url,
            $attachment->file_name,
            ['Content-Type' => $attachment->file_type]
        );
    }

    public function deleteAttachment(Attachment $attachment): void
    {
        Storage::disk(self::ATTACHMENTS_DISK)->delete($attachment->file_url);
        $attachment->delete();
    }

    private function generateFileName(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        return Str::uuid() . '.' . $extension;
    }

    private function generateFilePath(int $taskId, string $fileName): string
    {
        return sprintf(
            'tasks/%d/%s/%s',
            $taskId,
            date('Y/m/d'),
            $fileName
        );
    }
}
