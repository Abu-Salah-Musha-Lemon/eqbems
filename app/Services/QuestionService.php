<?php
namespace App\Services;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
class QuestionService
{
    public function validated(Request $request): array
    {
        $data = $request->validate([
            'type' => ['required', Rule::in(['mcq','true_false','short'])],
            'format' => ['nullable',Rule::in(['standard','creative'])],
            'stimulus'=>['nullable','string','max:12000'],
            'part_a'=>['nullable','string','max:3000'], 'part_b'=>['nullable','string','max:3000'],
            'part_c'=>['nullable','string','max:3000'], 'part_d'=>['nullable','string','max:3000'],
            'difficulty' => ['required', Rule::in(['easy','medium','hard'])],
            'body' => ['required','string','min:5','max:10000'],
            'marks' => ['required','numeric','min:0.01','max:99999.99','decimal:0,2'],
            'answer_text' => ['required','string','max:5000'],
            'explanation' => ['nullable','string','max:5000'],
            'option_a' => ['nullable','string','max:1500'],
            'option_b' => ['nullable','string','max:1500'],
            'option_c' => ['nullable','string','max:1500'],
            'option_d' => ['nullable','string','max:1500'],
        ]);
        $data['format'] = $data['format'] ?? 'standard';
        if ($data['format'] === 'creative') {
            if ($data['type'] !== 'short') {
                throw ValidationException::withMessages(['format'=>'Creative/CQ questions must use Short answer as the assessment type.']);
            }
            foreach (['a','b','c','d'] as $letter) {
                if (! filled($data['part_'.$letter] ?? null)) {
                    throw ValidationException::withMessages(['part_'.$letter=>'All four creative parts are required.']);
                }
            }
            if (! filled($data['stimulus'] ?? null)) {
                throw ValidationException::withMessages(['stimulus'=>'A creative question requires a stimulus (উদ্দীপক).']);
            }
            $data['creative_parts'] = [
                ['key'=>'ক','text'=>$data['part_a'],'marks'=>1],
                ['key'=>'খ','text'=>$data['part_b'],'marks'=>2],
                ['key'=>'গ','text'=>$data['part_c'],'marks'=>3],
                ['key'=>'ঘ','text'=>$data['part_d'],'marks'=>4],
            ];
            $data['marks'] = '10.00';
        } else {
            $data['stimulus'] = null;
            $data['creative_parts'] = null;
        }
        if ($data['type'] === 'mcq') {
            foreach (['a','b','c','d'] as $letter) {
                if (empty($data['option_'.$letter])) {
                    throw ValidationException::withMessages(['option_'.$letter => 'All four options are required for an MCQ.']);
                }
            }
            if (! in_array(strtoupper(trim($data['answer_text'])), ['A','B','C','D'], true)) {
                throw ValidationException::withMessages(['answer_text' => 'MCQ answer must be A, B, C or D.']);
            }
            $data['answer_text'] = strtoupper(trim($data['answer_text']));
            $data['options'] = ['A'=>$data['option_a'],'B'=>$data['option_b'],'C'=>$data['option_c'],'D'=>$data['option_d']];
        } elseif ($data['type'] === 'true_false') {
            if (! in_array(strtolower(trim($data['answer_text'])), ['true','false'], true)) {
                throw ValidationException::withMessages(['answer_text' => 'Answer must be True or False.']);
            }
            $data['answer_text'] = ucfirst(strtolower(trim($data['answer_text'])));
            $data['options'] = null;
        } else $data['options'] = null;
        return collect($data)->only(['type','difficulty','body','marks','answer_text','explanation','options','format','stimulus','creative_parts'])->all();
    }
}
