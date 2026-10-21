<?php

namespace Database\Seeders;

use App\Models\Institution;
use App\Models\Question;
use App\Models\QuestionVersion;
use App\Models\Subject;
use App\Models\Topic;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * 40 clearly identified DEMO questions (8 for each of 5 subjects).
 * These are pre-approved SAMPLE FIXTURES, not questions reviewed by a human.
 * Intended only for local/testing databases, to demonstrate exam generation.
 */
class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        // Make this seeder directly runnable even on a fresh, migrated database.
        $this->call(SubjectSeeder::class);

        $institution = Institution::where('code', SubjectSeeder::institutionCode())->firstOrFail();
        $inserted = 0;
        $skipped = 0;

        DB::transaction(function () use ($institution, &$inserted, &$skipped): void {
            foreach ($this->questionBank() as $subjectCode => $items) {
                $subject = Subject::where('institution_id', $institution->id)
                    ->where('code', $subjectCode)->firstOrFail();

                $topics = Topic::where('subject_id', $subject->id)
                    ->pluck('id', 'name');

                foreach ($items as $index => $item) {
                    $marker = sprintf('[EQBEMS-DEMO:%s:%02d]', $subjectCode, $index + 1);
                    $topicId = $topics->get($item['topic']);

                    if (! $topicId) {
                        throw new RuntimeException("Missing topic '{$item['topic']}' for subject {$subjectCode}.");
                    }

                    // Keep existing questions, versions, manual changes and reviews untouched.
                    // A stable marker in the initial version's note identifies each
                    // demo record even if someone later edits its question text.
                    $exists = Question::where('institution_id', $institution->id)
                        ->where('subject_id', $subject->id)
                        ->whereHas('versions', fn ($q) => $q
                            ->where('version_number', 1)
                            ->where('review_note', 'like', $marker . '%'))
                        ->exists();

                    if ($exists) {
                        $skipped++;
                        continue;
                    }

                    $question = Question::factory()->create([
                        'institution_id' => $institution->id,
                        'subject_id' => $subject->id,
                        'topic_id' => $topicId,
                        'author_id' => null,
                    ]);

                    QuestionVersion::factory()->for($question)->approvedDemo()->create([
                        'version_number' => 1,
                        'type' => $item['type'],
                        'difficulty' => $item['difficulty'],
                        'body' => $item['body'],
                        'options' => $item['options'],
                        'answer_text' => $item['answer'],
                        'explanation' => $item['explanation'],
                        'marks' => $item['marks'],
                        // DEMO FIXTURE: not an actual approval by a human reviewer.
                        'status' => 'approved',
                        'review_note' => $marker . ' Demo question pre-approved by sample data seeder; no human review performed.',
                        'created_by' => null,
                        'reviewed_by' => null,
                        'reviewed_at' => null,
                    ]);

                    $inserted++;
                }
            }
        });

        $this->command?->info("Demo question bank: {$inserted} created, {$skipped} already present.");
        $this->command?->warn('These questions are pre-approved demo fixtures, NOT human-reviewed examination content.');
    }

    private function mcq(string $topic, string $difficulty, string $body, array $options, string $answer, string $explanation): array
    {
        if (array_keys($options) !== ['A', 'B', 'C', 'D'] || ! array_key_exists($answer, $options)) {
            throw new RuntimeException('Every sample MCQ requires options A-D and an answer A-D.');
        }

        return compact('topic', 'difficulty', 'body', 'options', 'answer', 'explanation')
            + ['type' => 'mcq', 'marks' => 1];
    }

    private function tf(string $topic, string $difficulty, string $body, bool $answer, string $explanation): array
    {
        return [
            'topic' => $topic, 'difficulty' => $difficulty, 'body' => $body,
            'type' => 'true_false', 'options' => null,
            'answer' => $answer ? 'True' : 'False', 'explanation' => $explanation, 'marks' => 1,
        ];
    }

    private function short(string $topic, string $difficulty, string $body, string $answer, string $explanation): array
    {
        return compact('topic', 'difficulty', 'body', 'answer', 'explanation')
            + ['type' => 'short', 'options' => null, 'marks' => 5];
    }

    private function questionBank(): array
    {
        // Four MCQs, two true/false and two short-answer questions per subject.
        return [
            'CSE101' => [
                $this->mcq('Variables and Data Types', 'easy', 'Which data type stores whole numbers in C?', ['A'=>'float', 'B'=>'int', 'C'=>'char', 'D'=>'double'], 'B', 'The int type stores integer values.'),
                $this->mcq('Control Flow', 'easy', 'Which statement repeats a block while a condition remains true?', ['A'=>'if', 'B'=>'switch', 'C'=>'while', 'D'=>'return'], 'C', 'A while loop checks the condition before each iteration.'),
                $this->mcq('Functions', 'medium', 'What does a return statement do in a function?', ['A'=>'Creates an array', 'B'=>'Terminates the function and can send back a value', 'C'=>'Declares a variable', 'D'=>'Starts a loop'], 'B', 'Return exits the current function, optionally returning a value.'),
                $this->mcq('Arrays', 'medium', 'What is the index of the first element of a C array?', ['A'=>'0', 'B'=>'1', 'C'=>'-1', 'D'=>'2'], 'A', 'C arrays use zero-based indexing.'),
                $this->tf('Control Flow', 'easy', 'An if statement can run code conditionally.', true, 'The if statement executes its body when its condition is true.'),
                $this->tf('Arrays', 'medium', 'A C array can contain elements of unrelated data types.', false, 'A C array has elements of a single declared element type.'),
                $this->short('Functions', 'medium', 'Explain the difference between a function parameter and an argument.', 'A parameter is a variable declared in a function definition; an argument is the actual value or expression supplied at a call.', 'Mention definition versus call, with an example.'),
                $this->short('Control Flow', 'hard', 'Describe how a for loop executes its initialization, condition, update and body.', 'Initialization runs once; before each iteration the condition is tested; if true the body runs, then the update runs; this repeats until the condition is false.', 'The update occurs after the body in each iteration.'),
            ],
            'CSE201' => [
                $this->mcq('Stacks and Queues', 'easy', 'Which ordering principle does a stack use?', ['A'=>'FIFO', 'B'=>'LIFO', 'C'=>'Sorted order', 'D'=>'Random order'], 'B', 'Last In, First Out is the stack principle.'),
                $this->mcq('Stacks and Queues', 'easy', 'Which operation removes an element from the front of a queue?', ['A'=>'Push', 'B'=>'Peek', 'C'=>'Dequeue', 'D'=>'InsertAfter'], 'C', 'Dequeue removes the front item.'),
                $this->mcq('Algorithm Complexity', 'medium', 'What is the worst-case time complexity of binary search on a sorted array?', ['A'=>'O(n)', 'B'=>'O(1)', 'C'=>'O(n log n)', 'D'=>'O(log n)'], 'D', 'The search interval is halved each step.'),
                $this->mcq('Trees and Graphs', 'medium', 'Which data structure is typically used for breadth-first search?', ['A'=>'Queue', 'B'=>'Stack', 'C'=>'Heap', 'D'=>'Hash table'], 'A', 'BFS visits nodes in first-discovered order using a queue.'),
                $this->tf('Arrays and Linked Lists', 'easy', 'Accessing an array element by index usually takes constant time.', true, 'An array supports direct indexed access.'),
                $this->tf('Trees and Graphs', 'medium', 'Every binary tree is a binary search tree.', false, 'Binary search trees must satisfy an additional ordering property.'),
                $this->short('Arrays and Linked Lists', 'medium', 'Compare arrays and linked lists for random access and inserting at the beginning.', 'Arrays provide O(1) indexed access but typically O(n) insertion at the front; linked lists require O(n) traversal for indexed access but support O(1) insertion at the head when a head pointer is available.', 'Distinguish indexed access from insertion cost.'),
                $this->short('Algorithm Complexity', 'hard', 'Explain Big-O notation and give an example of O(n squared) behavior.', 'Big-O describes an asymptotic upper bound on growth with input size; a pair of nested loops each running n times performs O(n squared) iterations.', 'Use n as the input size and explain growth rather than elapsed time.'),
            ],
            'CSE301' => [
                $this->mcq('Relational Model', 'easy', 'Which database key uniquely identifies a row in a table?', ['A'=>'Foreign key', 'B'=>'Primary key', 'C'=>'Candidate value', 'D'=>'Index hint'], 'B', 'A primary key uniquely identifies each row and cannot be null.'),
                $this->mcq('SQL Queries', 'easy', 'Which SQL clause filters rows before grouping?', ['A'=>'WHERE', 'B'=>'ORDER BY', 'C'=>'HAVING', 'D'=>'LIMIT'], 'A', 'WHERE filters individual rows before aggregation.'),
                $this->mcq('Normalization', 'medium', 'Which normal form requires atomic attribute values?', ['A'=>'3NF', 'B'=>'BCNF', 'C'=>'1NF', 'D'=>'4NF'], 'C', 'First normal form requires each field to hold an atomic value in the relational model.'),
                $this->mcq('Transactions', 'medium', 'Which ACID property means committed transactions remain durable?', ['A'=>'Isolation', 'B'=>'Atomicity', 'C'=>'Consistency', 'D'=>'Durability'], 'D', 'Durability means committed changes survive failures under the database durability guarantees.'),
                $this->tf('SQL Queries', 'easy', 'ORDER BY sorts the result set of a SQL query.', true, 'ORDER BY specifies ascending or descending result ordering.'),
                $this->tf('Relational Model', 'medium', 'A foreign key must always reference a column in the same table.', false, 'Foreign keys usually reference a key in another table, though self-references are allowed.'),
                $this->short('Normalization', 'medium', 'What is database normalization and why is it used?', 'Normalization organizes relational tables to reduce unnecessary redundancy and avoid insertion, update and deletion anomalies.', 'Discuss redundancy and data anomalies.'),
                $this->short('Transactions', 'hard', 'Explain the difference between COMMIT and ROLLBACK in a transaction.', 'COMMIT makes transaction changes permanent; ROLLBACK cancels uncommitted changes and restores the transactional state according to the database isolation and transaction semantics.', 'The answer should distinguish completion from cancellation.'),
            ],
            'CSE401' => [
                $this->mcq('OSI and TCP/IP Models', 'easy', 'Which OSI layer is responsible for routing IP packets?', ['A'=>'Physical', 'B'=>'Network', 'C'=>'Session', 'D'=>'Application'], 'B', 'Routing is associated with the network layer.'),
                $this->mcq('Transport Protocols', 'easy', 'Which transport protocol provides reliable ordered byte-stream delivery?', ['A'=>'UDP', 'B'=>'ICMP', 'C'=>'TCP', 'D'=>'ARP'], 'C', 'TCP provides a reliable ordered byte stream.'),
                $this->mcq('IP Addressing', 'medium', 'How many bits are in an IPv4 address?', ['A'=>'32', 'B'=>'64', 'C'=>'48', 'D'=>'128'], 'A', 'IPv4 addresses have 32 bits.'),
                $this->mcq('Network Devices', 'medium', 'Which device primarily forwards Ethernet frames using MAC addresses?', ['A'=>'Modem', 'B'=>'Repeater', 'C'=>'Router', 'D'=>'Switch'], 'D', 'Layer 2 switches use MAC address tables to forward frames.'),
                $this->tf('Transport Protocols', 'easy', 'UDP guarantees delivery of every datagram.', false, 'UDP does not provide delivery guarantees.'),
                $this->tf('IP Addressing', 'medium', 'IPv6 addresses are 128 bits long.', true, 'IPv6 uses 128-bit addresses.'),
                $this->short('OSI and TCP/IP Models', 'medium', 'Explain the difference between the OSI model and the TCP/IP model.', 'OSI is a conceptual seven-layer reference model; TCP/IP is an Internet protocol suite commonly described using four or five layers and implemented in practice.', 'Name the difference in layers and their purpose.'),
                $this->short('Network Devices', 'hard', 'Compare a switch and a router in a typical LAN.', 'A switch primarily forwards link-layer frames within a network using MAC addresses; a router forwards IP packets between different networks using IP routing information.', 'Explain MAC versus IP forwarding and network scope.'),
            ],
            'CSE402' => [
                $this->mcq('Requirements Engineering', 'easy', 'Which requirement describes what a system must do?', ['A'=>'Functional requirement', 'B'=>'Performance constraint', 'C'=>'Availability target', 'D'=>'Usability metric'], 'A', 'Functional requirements describe services or behaviors.'),
                $this->mcq('Process Models', 'easy', 'Which software process model follows sequential development stages?', ['A'=>'Scrum', 'B'=>'Spiral', 'C'=>'Waterfall', 'D'=>'Kanban'], 'C', 'Waterfall organizes work in successive phases.'),
                $this->mcq('Software Testing', 'medium', 'What does unit testing primarily verify?', ['A'=>'Entire organization policy', 'B'=>'An individual component or unit of code', 'C'=>'User recruitment', 'D'=>'Server procurement'], 'B', 'Unit tests exercise small isolated pieces of functionality.'),
                $this->mcq('Version Control', 'medium', 'Which Git command creates a new branch named feature?', ['A'=>'git delete feature', 'B'=>'git merge feature', 'C'=>'git reset feature', 'D'=>'git branch feature'], 'D', 'git branch feature creates a new local branch without switching to it.'),
                $this->tf('Requirements Engineering', 'easy', 'Response time is an example of a non-functional requirement.', true, 'Response time expresses a performance quality constraint.'),
                $this->tf('Software Testing', 'medium', 'Black-box testing requires knowledge of the internal source code.', false, 'Black-box testing focuses on observable inputs and outputs.'),
                $this->short('Process Models', 'medium', 'State one advantage and one limitation of the waterfall model.', 'Advantage: stages and deliverables are clearly defined. Limitation: accommodating changed requirements late in development can be expensive.', 'Other correctly explained advantages and limitations are acceptable.'),
                $this->short('Software Testing', 'hard', 'Distinguish verification from validation in software engineering.', 'Verification checks that work products meet specified requirements (building the product right); validation checks that the delivered system meets intended user needs (building the right product).', 'Include the specification-versus-user-needs distinction.'),
            ],
        ];
    }
}
