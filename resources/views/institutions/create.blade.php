@extract('layouts.layouts')

@can('create', App\Models\Institution::class)

<a href="{{ route('institutions.create') }}"
   class="btn btn-primary">

    Add Institution

</a>

@endcan