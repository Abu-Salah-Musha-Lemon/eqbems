<?php

namespace Database\Seeders;

use App\Models\Institution;
use App\Models\Question;
use App\Models\QuestionVersion;
use App\Models\Subject;
use App\Models\Topic;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/** Optional original ICT-style questions illustrating board-layout printing. */
class BoardFormatDemoSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(SubjectSeeder::class);
        $institution = Institution::where('code',SubjectSeeder::institutionCode())->firstOrFail();
        DB::transaction(function () use ($institution) {
            $subject = Subject::where('institution_id',$institution->id)->where('code','HSC-ICT')->first();
            if (! $subject) $subject=Subject::factory()->for($institution)->create([
                'code'=>'HSC-ICT','name'=>'Information and Communication Technology (HSC)',
                'description'=>'Original demonstration content to exercise CQ and MCQ paper layouts; not taken from textbooks.',
            ]);
            $topic = Topic::where('subject_id',$subject->id)->where('name','Digital Technology and Number Systems')->first();
            if (! $topic) $topic=Topic::factory()->for($subject)->create(['name'=>'Digital Technology and Number Systems']);
            $items = [
                [
                    'type'=>'short','format'=>'creative','difficulty'=>'medium','marks'=>10,
                    'body'=>'Read the stimulus and answer questions ক–ঘ.',
                    'stimulus'=>'In an ICT lesson, a student converts a decimal number to binary. Another student argues that the hexadecimal number system is easier for programmers to read.',
                    'creative_parts'=>[
                        ['key'=>'ক','text'=>'What is a number system?','marks'=>1],
                        ['key'=>'খ','text'=>'Explain why computers commonly use binary.','marks'=>2],
                        ['key'=>'গ','text'=>'Convert (25)₁₀ into binary and show the steps.','marks'=>3],
                        ['key'=>'ঘ','text'=>'Evaluate the usefulness of hexadecimal representation compared with binary in programming.','marks'=>4],
                    ],
                    'answer_text'=>'Number systems represent numbers; binary is compatible with two-state digital circuits; 25 = 11001₂; hex groups four bits per digit for compact readable notation.',
                ],
                [
                    'type'=>'short','format'=>'creative','difficulty'=>'medium','marks'=>10,
                    'body'=>'Read the stimulus and answer questions ক–ঘ.',
                    'stimulus'=>'A school uses a local network to connect its computer lab. Teachers can access shared files and use a secure learning portal from separate classrooms.',
                    'creative_parts'=>[
                        ['key'=>'ক','text'=>'What is a computer network?','marks'=>1],
                        ['key'=>'খ','text'=>'Explain the role of a network switch.','marks'=>2],
                        ['key'=>'গ','text'=>'Describe how computers in the lab can share a common printer.','marks'=>3],
                        ['key'=>'ঘ','text'=>'Analyze two security measures the school should apply to its network.','marks'=>4],
                    ],
                    'answer_text'=>'A network connects devices; a switch forwards Ethernet frames based on MAC addresses; printer sharing uses a reachable printer/service and access control; examples: strong authentication, updates, segmentation, firewalls.',
                ],
                [
                    'type'=>'mcq','difficulty'=>'easy','marks'=>1,'body'=>'Which numeral system uses the digits 0 and 1?',
                    'options'=>['A'=>'Decimal','B'=>'Binary','C'=>'Octal','D'=>'Hexadecimal'],'answer_text'=>'B',
                ],
                [
                    'type'=>'mcq','difficulty'=>'easy','marks'=>1,'body'=>'How many bits form a byte?',
                    'options'=>['A'=>'2','B'=>'4','C'=>'8','D'=>'16'],'answer_text'=>'C',
                ],
                [
                    'type'=>'mcq','difficulty'=>'medium','marks'=>1,'body'=>'Which network device forwards frames using MAC address information?',
                    'options'=>['A'=>'Switch','B'=>'Repeater','C'=>'Scanner','D'=>'Projector'],'answer_text'=>'A',
                ],
                [
                    'type'=>'mcq','difficulty'=>'medium','marks'=>1,'body'=>'What is the hexadecimal representation of decimal 15?',
                    'options'=>['A'=>'10','B'=>'E','C'=>'F','D'=>'1F'],'answer_text'=>'C',
                ],
            ];
            foreach ($items as $index=>$item) {
                $marker=sprintf('[BOARD-FORMAT-DEMO:%02d]',$index+1);
                if (QuestionVersion::where('review_note','like',$marker.'%')->whereHas('question',fn ($q)=>$q->where('subject_id',$subject->id))->exists()) continue;
                $q=Question::factory()->create(['institution_id'=>$institution->id,'subject_id'=>$subject->id,'topic_id'=>$topic->id,'author_id'=>null]);
                QuestionVersion::factory()->for($q)->approvedDemo()->create(array_merge([
                    'version_number'=>1,'status'=>'approved','created_by'=>null,
                    'explanation'=>null,'options'=>null,'stimulus'=>null,'creative_parts'=>null,'format'=>'standard',
                ], $item, [
                    'review_note'=>$marker.' ORIGINAL DEMO format content — not human approved.',
                ]));
            }
        });
        $this->command?->info('HSC ICT print / online exam format demo: 2 creative + 4 MCQ questions are ready. Create an exam blueprint for HSC-ICT.');
    }
}
