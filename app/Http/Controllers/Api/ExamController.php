<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\ExamAnswer;
use App\Models\ExamQuestionAnswer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ExamResource;
use App\Http\Requests\StoreExamRequest;
use App\Http\Requests\UpdateExamRequest;
use App\Http\Requests\StoreExamAnswerRequest;
use Illuminate\Support\Arr;
use File;
use Str;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();

        return ExamResource::collection(Exam::where('user_id', $user->id)->paginate(3));
    }

    public function store(StoreExamRequest $request)
    {
        $data = $request->validated();

        //check image and save on file system
        if (isset($data['image'])) {
            $relativePath = $this->saveImage($data['image']);
            $data['image'] = $relativePath; 
        }

        $exam = Exam::create($data);

        //create question
        foreach ($data['questions'] as $question) {
            $question['exam_id'] = $exam->id;
            $this->createQuestion($question);
        }

        return new ExamResource($exam);
    }

  
    public function show(Exam $exam, Request $request)
    {
        $user = $request->user();
        if ($user->id !== $exam->user_id) {
            return abort(403, 'Unauthorized action');
        }
        return new ExamResource($exam);
    }

    public function showForGuest(Exam $exam)
    {
        if (!$exam->status) {
            return response("", 404);
        }
        $currentDate = new \DateTime();
        $expireDate = new \DateTime($exam->expire_date);
        if ($currentDate > $expireDate) {
            return response("", 404);
        }
        return new ExamResource($exam);
    }

    public function storeAnswer(StoreExamAnswerRequest $request, Exam $exam)
    {
        $validated = $request->validated();
        $examAnswer = ExamAnswer::create([
            'exam_id' => $exam->id,
            'start_date' => date('Y-m-d H:i:s'),
            'end_date' => date('Y-m-d H:i:s')
        ]);

        foreach ($validated['answers'] as $questionId => $answer) {
            $question = ExamQuestion::where(['id' => $questionId, 'exam_id' => $exam->id])->get();
            if (!$question) {
                return response("Invalid question ID: \"$questionId\"", 400);
            }
            $data = [
                'exam_question_id' => $questionId,
                'exam_answer_id' => $examAnswer->id,
                'answer' => is_array($answer) ? json_encode($answer) : $answer
            ];

            ExamQuestionAnswer::create($data);
        }
        
        return response("", 201);
    }

    public function update(UpdateExamRequest $request, Exam $exam)
    {
        $data = $request->validated();

        //check image and save on file system
        if (isset($data['image'])) {
            $relativePath = $this->saveImage($data['image']);
            $data['image'] = $relativePath; 

            if ($exam->image) {
                $absolutePath = public_path($exam->image);
                File::delete($absolutePath);
            }
        }
        

        $exam->update($data);

        //get id of existing question
            $existingIds = $exam->questions()->pluck('id')->toArray();
        //get ids of new question
            $newIds = Arr::pluck($data['questions'], 'id');
        //find question to delete
            $toDelete = array_diff($existingIds, $newIds);
        //find question to add
            $toAdd = array_diff($newIds, $existingIds);
        //delete question
            ExamQuestion::destroy($toDelete);

        //create new questions
            foreach ($data['questions'] as $question) {
                if (in_array($question['id'], $toAdd)) {
                    $question['exam_id'] = $exam->id;
                    $this->createQuestion($question);
                }
            }
        //update existing question
        $questionMap = collect($data['questions'])->keyBy('id');
        foreach ($exam->questions as $question) {
            if (isset($questionMap[$question->id])) {
                $this->updateQuestion($question, $questionMap[$question->id]);
            }
        }


        return new ExamResource($exam);
    }

    public function destroy(Exam $exam, Request $request)
    {
        $user = $request->user();
        if ($user->id !== $exam->user_id) {
            return abort(403, 'Unauthorized action');
        }
        if ($exam->image) {
            $absolutePath = public_path($exam->image);
            File::delete($absolutePath);
        }
        $exam->delete();
        return response('', 204); 
    }

    private function saveImage($image)
    {
        // check if image is base64 string
        if (preg_match('/^data:image\/(\w+);base64,/', $image, $type)) {
            $image = substr($image, strpos($image, ',') + 1); 
            $type = strtolower($type[1]);

            if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png'])) {
               
                throw new Exception("invalid image type");
                
            }
            $image = str_replace(' ', '+', $image);
            $image = base64_decode($image);

            if ($image === false) {
                throw new Exception("base64_decode failed");
                
            }
        }
        else{
            throw new Exception("Did not match data URI with image data");           
        }
        $dir = 'images/';
        $file = Str::random() . '.' .$type;
        $absolutePath = public_path($dir);
        $relativePath = $dir . $file;

        if (!File::exists($absolutePath)) {
            File::makeDirectory($absolutePath, 0755, true);
        }
        file_put_contents($relativePath, $image); 

        return $relativePath;
    }

    private function createQuestion($data)
    {
        if (is_array($data['data'])) {
            $data['data'] = json_encode($data['data']);
        }

        $validator = Validator::make($data, [
            'question' => 'required|string',
            'type' => ['required', Rule::in([
                Exam::TYPE_TEXT,
                Exam::TYPE_TEXTAREA,
                Exam::TYPE_SELECT,
                Exam::TYPE_RADIO,
                Exam::TYPE_CHECKBOX,

            ])],
            'description' => 'nullable|string',
            'data' => 'present',
            'exam_id' => 'exists:App\Models\Exam,id'
        ]);
        return ExamQuestion::create($validator->validated());
    }

    private function updateQuestion(ExamQuestion $question, $data)
    {
        if (is_array($data['data'])) {
            $data['data'] = json_encode($data['data']);
        }

        $validator = Validator::make($data, [
            'id' => 'exists:App\Models\ExamQuestion,id',
            'question' => 'required|string',
            'type' => ['required', Rule::in([
                Exam::TYPE_TEXT,
                Exam::TYPE_TEXTAREA,
                Exam::TYPE_SELECT,
                Exam::TYPE_RADIO,
                Exam::TYPE_CHECKBOX,

            ])],
            'description' => 'nullable|string',
            'data' => 'present'
        ]);
        return $question->update($validator->validated());

    }
}
