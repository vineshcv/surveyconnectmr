<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Helpers\ExportHelper;

use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $query = Question::query();

        if ($search = $request->input('search')) {
            $query->where('question', 'like', "%{$search}%")
                ->orWhere('type', 'like', "%{$search}%");
        }

        $questions = $query->latest()->paginate(10);
        return view('questions.index', compact('questions'));
    }


    public function create()
    {
        return view('questions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string',
            'type' => 'required|in:text,checkbox,multiselect,object',
            'options' => 'array|nullable',
            'sub_questions' => 'array|nullable'
        ]);

        $data = [
            'question' => $request->question,
            'type' => $request->type,
            'options' => in_array($request->type, ['checkbox', 'multiselect']) ? $request->options : null,
            'sub_questions' => $request->type === 'object' ? $request->sub_questions : null,
        ];

        Question::create($data);

        return redirect()->route('questions.index')->with('success', 'Question added successfully.');
    }

    public function show(Question $question)
    {
        return view('questions.show', compact('question'));
    }

    public function edit(Question $question)
    {
        return view('questions.edit', compact('question'));
    }

    public function update(Request $request, Question $question)
    {
        $request->validate([
            'question' => 'required|string',
            'type' => 'required|in:text,checkbox,multiselect,object',
            'options' => 'nullable|array',
            'sub_questions' => 'nullable|array',
        ]);

        $question->update([
            'question' => $request->question,
            'type' => $request->type,
            'options' => in_array($request->type, ['checkbox', 'multiselect']) ? $request->options : null,
            'sub_questions' => $request->type === 'object' ? $request->sub_questions : null,
        ]);

        return redirect()->route('questions.index')->with('success', 'Question updated successfully.');
    }

    public function toggleStatus(Question $question)
    {
        $question->status = !$question->status;
        $question->save();

        return redirect()->route('questions.index')->with('success', 'Question status updated.');
    }

    public function destroy(Question $question)
    {
        $question->delete();
        return redirect()->route('questions.index')->with('success', 'Question deleted.');
    }

    public function exportPdf()
    {
        $questions = Question::all();
        return ExportHelper::exportToPdf('questions.export-pdf', compact('questions'), 'questions.export-pdf');
    }

    public function exportCsv(): StreamedResponse
    {
        $questions = Question::all();

        $columns = ['Question', 'Type', 'Status'];

        $mapFn = function ($question) {
            return [
                $question->question,
                $question->type,
                $question->status ? 'Enabled' : 'Disabled',
            ];
        };

        return ExportHelper::exportToCsv($questions, $columns, $mapFn, 'questions.csv');
    }
}

