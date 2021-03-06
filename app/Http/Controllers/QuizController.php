<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use ErrorException;
use App\Models\Grade;
use App\Models\Course;
use App\Models\Quizze;
use App\Models\QuizType;
use App\Models\Quiz_result;
use Illuminate\Http\Request;
use App\Models\Quiz_question;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Quizze::with(['course', 'type'])
        ->select('quizzes.*')
        ->leftJoin('courses', 'courses.id', '=', 'quizzes.course_id')
        ->leftJoin('quiz_types', 'quiz_types.id', '=', 'quizzes.type_id');

        if ($this->auth('role_id') == 3) {
            $data = $data
            ->leftJoin('courses_users', 'courses_users.course_id', '=', 'courses.id')
            ->where('courses_users.user_id', $this->auth('id'));
        }

        $data = $data->get();

        $page_links = [];
        
        if ($this->auth('role_id') === 1 || $this->auth('role_id') === 2){
            $page_links = array_merge($page_links, [
                (object)['label' => 'Létrehozás', 'link' => '/admin/quiz/create'] ,
                (object)['label' => 'Feladat típusok listája', 'link' => 'admin/quiz-type'] ,
                (object)['label' => 'Törölt feladatok listája', 'link' => 'admin/quiz/deleted'] ,
            ] ,
            );
        }elseif($this->auth('role_id') == null) {
            return redirect()->to('/');
        }

        return view('quiz.quiz_list',[
            'isAdmin' => ($this->auth('role_id') === 1),
            'isTeacher' => ($this->auth('role_id') === 2),
            'isStudent' => ($this->auth('role_id') === 3),
            'items' => $data ,
            'page_title' => 'Feladatok' ,
            'page_subtitle' => 'Lista' ,
            'page_links' => $page_links,
        ]);
    }

    public function deleted()
    {
        $data = Quizze::with(['course', 'type'])
        ->select('quizzes.*')
        ->leftJoin('courses', 'courses.id', '=', 'quizzes.course_id')
        ->leftJoin('quiz_types', 'quiz_types.id', '=', 'quizzes.type_id');
        
        $data = $data->get();

        $page_links = [];

        if ($this->auth('role_id') === 3)
        return redirect()->to('/');

        return view('quiz.deleted_list',[            
            'isAdmin' => ($this->auth('role_id') === 1),
            'isTeacher' => ($this->auth('role_id') === 2),
            'isStudent' => ($this->auth('role_id') === 3),
            'items' => $data ,
            'page_title' => 'Feladatok' ,
            'page_subtitle' => 'Törölt elemek listája' ,
            'page_links' => $page_links,
        ]);
    }

    public function completion($id){
        $quiz = Quizze::where('id', $id)
        -> update(['started_at' => Carbon::now()]);

        $data = Quiz_question::where('quiz_id', $id)
        -> select('quiz_questions.*')
        -> get();


        
        $data = $data->map(function($item) {
            $item->answers = collect([
               (object)[ 'num' => 1, 'answer' => $item->answer_1 ],
               (object)[ 'num' => 2, 'answer' => $item->answer_2 ],
               (object)[ 'num' => 3, 'answer' => $item->answer_3 ],
               (object)[ 'num' => 4, 'answer' => $item->answer_4 ], 
            ])->shuffle();
            return $item;
        })->shuffle();
 
        return view('quiz.quiz_completion',[
            'id' => $id,
            'isAdmin' => ($this->auth('role_id') === 1),
            'isTeacher' => ($this->auth('role_id') === 2),
            'isStudent' => ($this->auth('role_id') === 3),
            'user' => ($this->auth('id')),
            'started_at' => Quizze::where('id', $id) -> select('quizzes.*')
            -> value('started_at'),
            'items' => $data ,
            'course_id' => $id,
            'page_title' => 'Feladatok' ,
            'page_subtitle' => 'Lista' ,
        ]);
    }   

    public function save_answers($id){

        $data = array();

        $quiz = Quizze::where('id', $id)
        -> update(['submitted_at' => Carbon::now()]);

        $questions = Quiz_question::where('quiz_id', $id)
        -> select('quiz_questions.*')
        -> get();

        foreach ($questions as $question) {

            try{$answer = $_POST[$question -> id];}
            catch (ErrorException $e){$answer = 0;}

            $new = Quiz_result::create([
                'quiz_question_id' => $question -> id,
                'answer' => $answer,
                'user_id' => $this->auth('id'),
            ]);

            $data = null;
            if ($this->auth('role_id') === 1 || $this->auth('role_id') === 2){
                $data = Quiz_result::join('quiz_questions', 'quiz_results.quiz_question_id', '=', 'quiz_questions.id')->get();
            } else if($this->auth('role_id') === 3){
                $data = Quiz_result::with(['quiz_question'])
                -> where('user_id', $this->auth('id'))
                -> leftJoin('quiz_questions', 'quiz_results.quiz_question_id', '=', 'quiz_questions.id')
                -> get();
            }

        }   

        return redirect()->to('quiz/rating/'.$id);
    } 

    public function show_answers($id)
    {
  
        $data = array();

        $quiz = Quizze::where('id', $id)
        -> update(['submitted_at' => Carbon::now()]);

        $questions = Quiz_question::where('quiz_id', $id)
        -> select('quiz_questions.*')
        -> get();

          $data = null;
            if ($this->auth('role_id') === 1 || $this->auth('role_id') === 2){
                $data = Quiz_result::join('quiz_questions', 'quiz_results.quiz_question_id', '=', 'quiz_questions.id')->get();
            } else if($this->auth('role_id') === 3){
                $data = Quiz_result::with(['quiz_question'])
                -> where('user_id', $this->auth('id'))
                -> leftJoin('quiz_questions', 'quiz_results.quiz_question_id', '=', 'quiz_questions.id')
                -> get();
            }

         $isGradeExists = false;

         $isGradeExists = Grade::where('quiz_id', $id)
         -> where('user_id', $this->auth('id'))
         -> get()
         -> count() != 0;

        return view('quiz.quiz_answers',[
            'quiz_id' => $id,
            'isAdmin' => ($this->auth('role_id') === 1),
            'isTeacher' => ($this->auth('role_id') === 2),
            'isStudent' => ($this->auth('role_id') === 3),
            'user_id' => ($this->auth('id')),
            'started_at' => Quizze::where('id', $id) -> select('quizzes.*')
            -> value('started_at'),
            'submitted_at' => Quizze::where('id', $id) -> select('quizzes.*')
            -> value('submitted_at'),
            'items' => $data ,
            'course_id' => $id,
            'page_title' => 'Válaszok' ,
            'page_subtitle' => 'Lista' ,
            'saved' => $isGradeExists
        ]);
    }

    public function show_result($id){

        $quiz_results = null;
        if ($this->auth('role_id') === 1 || $this->auth('role_id') === 2){
            $quiz_results = Quiz_result::join('quiz_questions', 'quiz_results.quiz_question_id', '=', 'quiz_questions.id')
            -> get();
        } else if($this->auth('role_id') === 3){
            $quiz_results = Quiz_result::with(['quiz_question'])
            -> where('user_id', $this->auth('id'))
            -> leftJoin('quiz_questions', 'quiz_results.quiz_question_id', '=', 'quiz_questions.id')
            -> get();          
        }

        $data = Grade::with(['user'])
        ->where('quiz_id', $id) 
        ->leftJoin('users', 'users.id', '=', 'grades.user_id')
        ->get();
        $user_grade = Grade::where('quiz_id', $id)->where('user_id', $this->auth('id'))->first();
        $user_grade = $user_grade != null ? $user_grade->grade : null;

        $detailedQuizResult = null;
        foreach ($data as &$grade) {
            
            $detailedQuizResult = Quiz_Result::with(['quiz_question'])
            ->join('quiz_questions', 'quiz_questions.id', '=', 'quiz_results.quiz_question_id')
            ->join('quizzes', 'quiz_questions.quiz_id', '=', 'quizzes.id')
            ->where('quiz_results.user_id','=',$grade->user_id)
            ->where('quizzes.id','=',$id)
            -> get();
                    
            $grade->{"quiz_result"} =  $detailedQuizResult;
        }

        return view('quiz.quiz_result',[
            'id' => $id,
            'isAdmin' => ($this->auth('role_id') === 1),
            'isTeacher' => ($this->auth('role_id') === 2),
            'isStudent' => ($this->auth('role_id') === 3),
            'user_id' => ($this->auth('id')),
            'items' => $quiz_results ,
            'grades' => $data,
            'course_id' => $id,
            'quiz' => $detailedQuizResult,
            'page_title' => 'Eredmeny' ,
            'page_subtitle' => 'Lista' ,
            'user_grade' => $user_grade , 
        ]);
    }   

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        if ($this->auth('role_id') !== 1 && $this->auth('role_id') !== 2) {
            return redirect()->to('/');
        }

        $request->validate([
            'started_at'          =>      'required',
            'submitted_at'        =>      'required',
            'question'            =>      'required|array',
            'answer_1'            =>      'required|array',
            'answer_2'            =>      'required|array',
            'answer_3'            =>      'required|array',
            'answer_4'            =>      'required|array',
            'correct_answer'      =>      'required|array',
        ]);

        $new = Quizze::create([
            'started_at' => $request->started_at,
            'submitted_at' => $request->submitted_at,
            'type_id' => $request->type_id,
            'course_id' => $request->course_id,
            'quizType' => $request->quizType,
        ]);
        if (!is_null($new)) {        
            $new->save();

            $quiz_id = $new -> id;

            for($i = 1; $i<11;$i++){
                if (
                    trim($request->question[$i]) != '' &&
                    trim($request->answer_1[$i]) != '' &&
                    trim($request->answer_2[$i]) != '' &&
                    trim($request->answer_3[$i]) != '' &&
                    trim($request->answer_4[$i]) != '' &&
                    isset($request->correct_answer[$i]) != ''
                ) {
                $new = Quiz_question::create([
                    'question' => $request->question[$i],
                    'answer_1' => $request->answer_1[$i],
                    'answer_2' => $request->answer_2[$i],
                    'answer_3' => $request->answer_3[$i],
                    'answer_4' => $request->answer_4[$i],
                    'correct_answer' => $request->correct_answer[$i],
                    'quiz_id' => $quiz_id,
                ]);
                }
            }

            return redirect()->to('/course/'.$request->course_id.'/quiz/');
        } else {
            return back()->with('error', 'Hoppá, hiba történt. Próbáld újra.');
        }
    }

    public function create_form($course_id)
    {
        if ($this->auth('role_id') !== 1 && $this->auth('role_id') !== 2) {
            return redirect()->to('/');
        }

        $types = QuizType::get();
        $courses = Course::get();

            return view('quiz.quiz_create',[

                'types' => $types,
                'courses' => $courses,
                'course_id' => $course_id,
                'page_title' => 'Feladatok' ,
                'page_subtitle' => 'Létrehozás',
            ]);
            

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if ($this->auth('role_id') !== 1 && $this->auth('role_id') !== 2) {
            return redirect()->to('lesson_list');
        }

        $data = Quizze::where('id', $id)->first();

        $types = QuizType::get();

        $courses = Course::get();

        $questions = Quiz_question::where('quiz_id', $id)->get();

        return view('quiz.quiz_edit',[
            'id' => $data -> id,
            'started_at' => $data -> started_at,
            'submitted_at' => $data -> submitted_at,
            'type_id' => $data -> type_id,
            'course_id' => $data -> course_id,
            'types' => $types,
            'courses' => $courses,
            'questions' => $questions,
            'quizType' => $data -> quizType,
            'page_title' => 'Feladatok' ,
            'page_subtitle' => 'Szerkesztés' ,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($this->auth('role_id') !== 1 && $this->auth('role_id') !== 2) {
            return redirect()->to('lesson_list');
        }


        $request->validate([
            'started_at'          =>      'required',
            'submitted_at'        =>      'required',
            'question'            =>      'required|array',
            'answer_1'            =>      'required|array',
            'answer_2'            =>      'required|array',
            'answer_3'            =>      'required|array',
            'answer_4'            =>      'required|array',
            'correct_answer'      =>      'required|array',
        ]);

        $new = Quizze::where('id', $id) -> update([
            'started_at' => $request->started_at,
            'submitted_at' => $request->submitted_at,
            'type_id' => $request->type_id,
            'course_id' => $request->course_id,
            'quizType' => $request->quizType,
        ]);

        for($i = 0; $i<10;$i++){
            if (
                isset($request->question[$i]) && trim($request->question[$i]) != '' &&
                isset($request->answer_1[$i]) && trim($request->answer_1[$i]) != '' &&
                isset($request->answer_2[$i]) && trim($request->answer_2[$i]) != '' &&
                isset($request->answer_3[$i]) && trim($request->answer_3[$i]) != '' &&
                isset($request->answer_4[$i]) && trim($request->answer_4[$i]) != '' &&
                isset($request->correct_answer[$i]) != ''
            ) {
                $new_question = Quiz_question::where('id', $request->question_id[$i]) -> update([
                    'question' => $request->question[$i],
                    'answer_1' => $request->answer_1[$i],
                    'answer_2' => $request->answer_2[$i],
                    'answer_3' => $request->answer_3[$i],
                    'answer_4' => $request->answer_4[$i],
                    'correct_answer' => $request->correct_answer[$i],
                ]);
                if (is_null($new_question)) {
                    return back()->with('error', 'Hoppá, hiba történt. Próbáld újra.');
                }
            }
        }

        for($i = 0; $i<10;$i++){
            if (
                isset($request->new_question[$i]) && trim($request->new_question[$i]) != '' &&
                isset($request->new_answer_1[$i]) && trim($request->new_answer_1[$i]) != '' &&
                isset($request->new_answer_2[$i]) && trim($request->new_answer_2[$i]) != '' &&
                isset($request->new_answer_3[$i]) && trim($request->new_answer_3[$i]) != '' &&
                isset($request->new_answer_4[$i]) && trim($request->new_answer_4[$i]) != '' &&
                isset($request->new_correct_answer[$i]) != ''
            ) {
            $new = Quiz_question::create([
                'question' => $request->new_question[$i],
                'answer_1' => $request->new_answer_1[$i],
                'answer_2' => $request->new_answer_2[$i],
                'answer_3' => $request->new_answer_3[$i],
                'answer_4' => $request->new_answer_4[$i],
                'correct_answer' => $request->new_correct_answer[$i],
                'quiz_id' => $id,
            ]);
            }
        }

        if (!is_null($new)) {
        return redirect()->to('/course/'.$request->course_id.'/quiz/');
        } else {
            return back()->with('error', 'Hoppá, hiba történt. Próbáld újra.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function undo_delete($id)
    {
        if ($this->auth('role_id') == 1 || $this->auth('role_id') == 2) {
            Quiz_question::where('quiz_id', $id)->update([
                'deleted_at' => NULL
            ]);
            
            Quizze::where('id', $id)->update([
                'deleted_at' => NULL
            ]);
        }
        return redirect()->to('/quiz');
    }

    public function destroy($id)
    {
        if ($this->auth('role_id') == 1 || $this->auth('role_id') == 2) {
            Quiz_question::where('quiz_id', $id)->update([
                'deleted_at' => Carbon::now()
            ]);

            Quizze::where('id', $id)->update([
                'deleted_at' => Carbon::now()
            ]);
        }
        return redirect()->to('/quiz');
    }
}
