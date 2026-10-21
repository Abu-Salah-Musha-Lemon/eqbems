@extends('layouts.admin')
@section('title','Restricted answer key')
@section('content')
<div class="mb-4"><div class="eyebrow">Confidential / Staff only</div><h1 class="page-title">Answer key: {{ $exam->title }}</h1><p class="muted">Do not distribute to candidates.</p></div>
<div class="card-pro table-card"><table class="table"><thead><tr><th>Question</th><th>Answer</th><th>Marks</th></tr></thead><tbody>@foreach($exam->questions as $item)<tr><td>{{ $item->position }}. {{ \Illuminate\Support\Str::limit($item->content_snapshot['body'],80) }}</td><td>{{ $item->answer_snapshot }}</td><td>{{ $item->marks }}</td></tr>@endforeach</tbody></table></div>
@endsection
