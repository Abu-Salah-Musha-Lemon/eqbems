@extends('layouts.admin')
@section('title','Exam blueprints')
@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <div class="eyebrow">Examinations / Planning</div>
        <h1 class="page-title">Blueprints</h1>
        <p class="muted mb-0">Plan a question paper, check its approved question pool, then generate the exam.</p>
    </div>
    @can('blueprint.manage')
        <a class="btn btn-primary" href="{{ route('blueprints.create') }}"><i class="bi bi-plus-circle me-1"></i> New blueprint</a>
    @endcan
</div>

@if(session('warning'))
    <div class="alert alert-warning" role="alert">{{ session('warning') }}</div>
@endif

<div class="card-pro table-card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Paper / Subject</th>
                    <th>Requested</th>
                    <th>Approved pool</th>
                    <th>Duration</th>
                    <th>Filters</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($blueprints as $blueprint)
                    @php
                        $eligible = $availableCounts[$blueprint->id] ?? 0;
                        $pinned = $selectionCounts[$blueprint->id] ?? 0;
                        $invalid = $invalidSelectionCounts[$blueprint->id] ?? 0;
                        $isReady = $eligible >= $blueprint->question_count && $invalid === 0 && $pinned <= $blueprint->question_count;
                    @endphp
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $blueprint->title }}</div>
                            <small class="muted">{{ $blueprint->subject?->name ?? 'Unavailable subject' }}</small>
                        </td>
                        <td>{{ $blueprint->question_count }}</td>
                        <td>
                            @if($isReady)
                                <span class="badge text-bg-success">{{ $eligible }} available</span>
                            @else
                                <span class="badge text-bg-warning">{{ $eligible }} available</span>
                                @if($eligible < $blueprint->question_count)
                                    <small class="d-block text-danger mt-1">Need {{ $blueprint->question_count - $eligible }} more</small>
                                @endif
                            @endif
                            <small class="d-block muted mt-1">{{ $pinned }} manually selected</small>
                            @if($invalid > 0)<small class="d-block text-danger">{{ $invalid }} selected no longer eligible</small>@endif
                        </td>
                        <td>{{ $blueprint->duration_minutes }} min</td>
                        <td>
                            <small>{{ ucfirst($blueprint->difficulty ?? 'Any difficulty') }}<br>
                                {{ strtoupper(str_replace('_', ' ', $blueprint->type ?? 'Any type')) }} · {{ ucfirst($blueprint->format ?? 'Any format') }}
                            </small>
                        </td>
                        <td>
                            <div class="d-flex flex-wrap justify-content-end gap-2">
                                @can('blueprint.view')
                                    @can('question.view')
                                        <a class="btn btn-sm btn-outline-primary" href="{{ route('blueprints.questions.index', $blueprint) }}"><i class="bi bi-list-check me-1"></i> Manage questions</a>
                                    @endcan
                                @endcan
                                @can('blueprint.manage')
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('blueprints.edit', $blueprint) }}">Edit</a>
                                @endcan
                                @can('exam.generate')
                                    <form action="{{ route('exams.generate', $blueprint) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-sm btn-primary" @disabled(!$isReady)
                                            title="{{ $isReady ? 'Create a new paper' : 'Resolve missing approved questions or invalid selections first' }}">Generate exam</button>
                                    </form>
                                @endcan
                                @can('blueprint.manage')
                                    <form action="{{ route('blueprints.destroy', $blueprint) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                            data-confirm="Delete this blueprint? Blueprints with exam history cannot be deleted.">Delete</button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-5 muted">No blueprints yet. Create your first blueprint to plan an exam.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $blueprints->links() }}</div>
</div>
<p class="small muted mt-3">Use <strong>Manage questions</strong> to create, submit, approve (independent reviewer only) or select questions. Only approved question versions count. A new draft version does not invalidate the most recently approved version. Different filters may reduce the available pool.</p>
@endsection
