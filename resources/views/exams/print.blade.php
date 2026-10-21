<!doctype html>
<html lang="bn"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ $exam->title }} — Print question paper</title>
<style>
@page{size:A4;margin:14mm 13mm 15mm}
:root{--ink:#101828;--line:#64748b}
*{box-sizing:border-box}body{margin:0;color:var(--ink);background:#edf2f6;font:13px/1.55 'Noto Sans Bengali','SolaimanLipi','Vrinda','Kalpurush',Arial,sans-serif}
.tools{max-width:900px;margin:16px auto;display:flex;justify-content:space-between;align-items:center;gap:12px}.tools button,.tools a{border:0;border-radius:8px;padding:10px 18px;background:#1d4ed8;color:white;cursor:pointer;text-decoration:none}
.sheet{max-width:210mm;min-height:297mm;margin:0 auto 28px;padding:15mm 14mm;background:white;box-shadow:0 8px 24px #47556930}
.school{text-align:center;font-size:18px;font-weight:700;line-height:1.4}.exam-label{text-align:center;font-size:15px;font-weight:700;margin-top:4px}.subject{text-align:center;font-size:14px;margin:3px 0 12px}
.meta{display:flex;justify-content:space-between;border-top:1px solid #111;border-bottom:1px solid #111;padding:6px 0;margin:10px 0;gap:12px;font-weight:bold}.candidate{display:flex;justify-content:space-between;margin:8px 0;font-size:12px}.rules{white-space:pre-wrap;margin:10px 0;border:1px solid #cbd5e1;padding:8px 12px}
.heading{margin:16px 0 9px;text-align:center;font-weight:700;border-bottom:1px solid black;padding-bottom:5px}
.q{break-inside:avoid;page-break-inside:avoid;margin:12px 0 15px}.qline{display:flex;gap:7px;align-items:flex-start}.num{font-weight:700;min-width:25px}.marks{white-space:nowrap;margin-left:auto;font-weight:bold}.body{flex:1;white-space:pre-wrap}.options{display:grid;grid-template-columns:1fr 1fr;column-gap:18px;row-gap:5px;margin:6px 0 0 29px}.opt{white-space:pre-wrap}
.stimulus{white-space:pre-wrap;border:1px solid #cbd5e1;border-radius:3px;background:#fafafa;padding:9px 12px;margin:8px 0 10px 28px}.parts{margin-left:28px}.part{display:flex;gap:8px;align-items:flex-start;margin:5px 0}.part .text{flex:1;white-space:pre-wrap}
.paper-compact_mcq .question-grid{columns:2;column-gap:18px;column-rule:1px solid #ddd}.paper-compact_mcq .q{display:inline-block;width:100%;font-size:11.4px;line-height:1.4;margin:9px 0}.paper-compact_mcq .options{grid-template-columns:1fr 1fr;margin-left:25px;font-size:10.9px;gap:3px 8px}.paper-compact_mcq .marks{font-size:10px}
.footer{text-align:center;border-top:1px solid #bbb;padding-top:7px;margin-top:28px;font-size:10px;color:#667085}
@media print{body{background:white}.tools{display:none}.sheet{box-shadow:none;max-width:none;min-height:unset;margin:0;padding:0}.q{break-inside:avoid}a{display:none}}
</style></head>
<body class="paper-{{ $exam->print_layout ?? 'standard' }}">
<div class="tools"><span>Student-facing paper · Answer key excluded</span><div><button onclick="window.print()">Print / Save as PDF</button></div></div>
<main class="sheet">
<header><div class="school">{{ $exam->institution->name ?? config('app.name','EQBEMS') }}</div>
<div class="exam-label">{{ $exam->exam_label ?: $exam->title }}</div>
<div class="subject">@if($exam->class_label){{ $exam->class_label }} · @endif {{ $exam->subject->name }} @if($exam->paper_code)(Subject Code: {{ $exam->paper_code }})@endif</div>
<div class="meta"><span>Time: {{ $exam->duration_minutes }} minutes</span><span>Full Marks: {{ (float)$exam->total_marks }}</span></div>
<div class="candidate"><span>Name: ________________________</span><span>Roll: ____________________</span><span>Class/Section: ____________________</span></div></header>
@if($exam->instructions)<div class="rules"><strong>Instructions:</strong> {{ $exam->instructions }}</div>@endif
<div class="heading">{{ ($exam->print_layout ?? '') === 'compact_mcq' ? 'Multiple Choice Questions / বহুনির্বাচনি প্রশ্ন' : ((($exam->print_layout ?? '') === 'creative') ? 'Creative Questions / সৃজনশীল প্রশ্ন' : 'Question Paper / প্রশ্নপত্র') }}</div>
<div class="question-grid">
@foreach($exam->questions as $item)
@php($c=$item->content_snapshot)
<div class="q">
<div class="qline"><span class="num">{{ $item->position }}.</span><span class="body">{{ $c['body'] ?? '' }}</span><span class="marks">[{{ (float)$item->marks }}]</span></div>
@if(($c['format'] ?? '') === 'creative')
<div class="stimulus"><strong>উদ্দীপক:</strong> {{ $c['stimulus'] ?? '' }}</div>
<div class="parts">@foreach($c['creative_parts'] ?? [] as $part)
<div class="part"><strong>{{ $part['key'] }}.</strong><span class="text">{{ $part['text'] }}</span><strong>({{ $part['marks'] }})</strong></div>
@endforeach</div>
@elseif(($c['type'] ?? '') === 'mcq')
<div class="options">@foreach(($c['options'] ?? []) as $key=>$value)<div class="opt"><strong>{{ ['A'=>'ক','B'=>'খ','C'=>'গ','D'=>'ঘ'][$key] ?? $key }}.</strong> {{ $value }}</div>@endforeach</div>
@elseif(($c['type'] ?? '') === 'true_false')
<div class="options"><div>○ True / সত্য</div><div>○ False / মিথ্যা</div></div>
@endif
</div>
@endforeach
</div>
<footer class="footer">End of question paper · {{ $exam->title }} · Please check the number of questions.</footer>
</main></body></html>
