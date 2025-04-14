<?php

namespace App\Http\Controllers;

use App\Models\QuestionAttachment;
use Exception;
use Illuminate\Support\Facades\Storage;

class QuestionAttachmentsController extends Controller
{
    public function show($id)
    {
        $questionAttachment = QuestionAttachment::findOrFail($id);

        if (! \Auth::user()->isAdmin()) {
            abort_unless((bool) $questionAttachment->question->quiz->opened_at, 403);
        }

        try {
            return response()->file(Storage::path($questionAttachment->path));
        } catch (Exception $e) {
            return abort(500, $e->getMessage());
        }
    }
}
