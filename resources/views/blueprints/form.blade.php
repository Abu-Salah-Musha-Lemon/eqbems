@extends('layouts.admin')
@section('title', $blueprint->exists ? 'Edit blueprint' : 'New blueprint')
@section('content')
@php($editing = $blueprint->exists)
<div class="mb-4">
    <div class="eyebrow">Examinations / Planning</div>
    <h1 class="page-title">{{ $editing ? 'Edit exam blueprint' : 'Create exam blueprint' }}</h1>
    <p class="muted">Choose the number of questions and filters; EQBEMS checks the approved question bank before generation.</p>
</div>

@if($subjects->isEmpty())
    <div class="alert alert-warning">No subjects are available for this institution. Add a subject first.</div>
    <a href="{{ route('blueprints.index') }}" class="btn btn-light">Back</a>
@else
<form id="blueprintForm" method="POST" action="{{ $editing ? route('blueprints.update', $blueprint) : route('blueprints.store') }}">
    @csrf
    @if($editing) @method('PUT') @endif
    <div class="card-pro p-4">
        <div class="row g-3">
            <div class="col-12">
                <label for="title" class="form-label fw-semibold">Paper title</label>
                <input id="title" name="title" type="text" maxlength="255" class="form-control @error('title') is-invalid @enderror"
                       value="{{ old('title', $blueprint->title) }}" placeholder="Information & Communication Technology – Test 1" required>
                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label for="subject_id" class="form-label fw-semibold">Subject</label>
                <select id="subject_id" name="subject_id" class="form-select @error('subject_id') is-invalid @enderror" required>
                    <option value="">Choose subject…</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" @selected((string) old('subject_id', $blueprint->subject_id) === (string) $subject->id)>
                            {{ $subject->name }} ({{ $subject->code }})
                        </option>
                    @endforeach
                </select>
                @error('subject_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label for="question_count" class="form-label fw-semibold">Number of questions</label>
                <input id="question_count" type="number" min="1" max="200" name="question_count"
                       class="form-control @error('question_count') is-invalid @enderror"
                       value="{{ old('question_count', $blueprint->question_count ?? 10) }}" required>
                @error('question_count')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label for="duration_minutes" class="form-label fw-semibold">Duration (minutes)</label>
                <input id="duration_minutes" type="number" min="1" max="600" name="duration_minutes"
                       class="form-control @error('duration_minutes') is-invalid @enderror"
                       value="{{ old('duration_minutes', $blueprint->duration_minutes ?? 60) }}" required>
                @error('duration_minutes')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label for="type" class="form-label fw-semibold">Question type</label>
                <select id="type" name="type" class="form-select @error('type') is-invalid @enderror">
                    <option value="">All types</option>
                    @foreach(['mcq' => 'MCQ', 'true_false' => 'True / False', 'short' => 'Short Answer'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('type', $blueprint->type) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label for="format" class="form-label fw-semibold">Paper format</label>
                <select id="format" name="format" class="form-select @error('format') is-invalid @enderror">
                    <option value="">All formats</option>
                    <option value="standard" @selected(old('format', $blueprint->format) === 'standard')>Standard (MCQ / short)</option>
                    <option value="creative" @selected(old('format', $blueprint->format) === 'creative')>Creative / CQ (ক, খ, গ, ঘ)</option>
                </select>
                @error('format')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label for="difficulty" class="form-label fw-semibold">Difficulty</label>
                <select id="difficulty" name="difficulty" class="form-select @error('difficulty') is-invalid @enderror">
                    <option value="">Any difficulty</option>
                    @foreach(['easy', 'medium', 'hard'] as $difficulty)
                        <option value="{{ $difficulty }}" @selected(old('difficulty', $blueprint->difficulty) === $difficulty)>{{ ucfirst($difficulty) }}</option>
                    @endforeach
                </select>
                @error('difficulty')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div id="availabilityBox" class="alert alert-light border mt-4 mb-0" role="status" aria-live="polite">
            <strong>Approved questions matching your selections:</strong>
            <span id="availableCount">Choose a subject</span>
            <div id="availabilityHelp" class="small mt-1 text-secondary">The count updates when you change subject, type, difficulty, or format.</div>
        </div>

        @if($editing)
            @error('filters')<div class="alert alert-danger mt-3">{{ $message }}</div>@enderror
            <p class="small muted mt-3 mb-0">Changing this blueprint affects future generation only. Existing generated and issued papers keep their question snapshots.</p>
        @endif
        <div class="d-flex gap-2 mt-4">
            <button class="btn btn-primary">{{ $editing ? 'Save changes' : 'Save blueprint' }}</button>
            @if($editing && auth()->user()->can('question.view'))
                <a class="btn btn-outline-primary" href="{{ route('blueprints.questions.index', $blueprint) }}">Manage questions</a>
            @endif
            <a class="btn btn-light" href="{{ route('blueprints.index') }}">Cancel</a>
        </div>
    </div>
</form>
@endif
@endsection

@push('scripts')
<script>
(() => {
    const form = document.getElementById('blueprintForm');
    if (!form) return;
    const count = document.getElementById('availableCount');
    const help = document.getElementById('availabilityHelp');
    const box = document.getElementById('availabilityBox');
    const url = @json(route('blueprints.availability'));
    let controller;
    let sequence = 0;

    const refresh = async () => {
        const current = ++sequence;
        if (controller) controller.abort();
        controller = new AbortController();
        const data = new FormData(form);
        const requested = Number(data.get('question_count'));
        const subject = data.get('subject_id');
        box.className = 'alert alert-light border mt-4 mb-0';
        if (!subject) {
            count.textContent = 'Choose a subject';
            help.textContent = 'Select a subject to check the approved question pool.';
            return;
        }
        count.textContent = 'Checking…';
        const params = new URLSearchParams();
        ['subject_id','type','format','difficulty'].forEach(key => params.set(key, data.get(key) || ''));
        try {
            const response = await fetch(url + '?' + params, {
                headers: {'Accept': 'application/json'},
                credentials: 'same-origin', signal: controller.signal,
            });
            if (current !== sequence) return;
            if (!response.ok) {
                count.textContent = 'Unavailable';
                help.textContent = response.status === 422
                    ? 'Creative/CQ format requires Short Answer or All types.'
                    : 'Could not check availability. The server will validate on save.';
                box.className = 'alert alert-warning mt-4 mb-0';
                return;
            }
            const result = await response.json();
            if (current !== sequence) return;
            const available = Number(result.available);
            count.textContent = available;
            if (Number.isInteger(requested) && requested > 0 && available < requested) {
                box.className = 'alert alert-warning mt-4 mb-0';
                help.textContent = `Only ${available} match. Add ${requested - available} more approved questions or lower the count/change the filters before generating.`;
            } else {
                box.className = 'alert alert-success mt-4 mb-0';
                help.textContent = 'Enough questions match the selected filters to generate a paper.';
            }
        } catch (error) {
            if (error.name === 'AbortError' || current !== sequence) return;
            count.textContent = 'Unavailable';
            help.textContent = 'Could not check availability. The server will validate on generation.';
            box.className = 'alert alert-warning mt-4 mb-0';
        }
    };
    ['subject_id','type','format','difficulty','question_count'].forEach(name => {
        form.elements.namedItem(name)?.addEventListener('change', refresh);
    });
    refresh();
})();
</script>
@endpush
