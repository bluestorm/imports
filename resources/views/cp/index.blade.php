@extends('statamic::layout')
@section('title', 'Imports')

@section('content')
    <div class="flex items-center justify-between mb-3">
        <h1>Imports</h1>
        <a href="{{ cp_route('imports.new') }}" class="btn-primary">{{ __('Create Import') }}</a>
    </div>

    <div class="card p-0">
        <table class="data-table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>CSV / XML / JSON</th>
                <th>Collection Handle</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($imports as $import)
                <tr>
                    <td>{{ $import->id }}</td>
                    <td>
                        <a href="{{ cp_route('imports.edit', $import->id) }}">{{ $import->name }}</a>
                    </td>
                    <td>{{ $import->file }}</td>
                    <td>{{ $import->collectionHandle }}</td>
                    <td>
                        <a href="{{ cp_route('imports.map', $import->id) }}" title="Map Fields"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18" height="18" width="18"><g transform="matrix(1.0714285714285714,0,0,1.0714285714285714,0,0)"><g><path d="M11.5,5c0,2.49-4.5,8.5-4.5,8.5S2.5,7.49,2.5,5a4.5,4.5,0,0,1,9,0Z" style="fill: none;stroke: #000000;stroke-linecap: round;stroke-linejoin: round"></path><circle cx="7" cy="5" r="1.5" style="fill: none;stroke: #000000;stroke-linecap: round;stroke-linejoin: round"></circle></g></g></svg></a> &nbsp;
                        <a href="{{ cp_route('imports.import', $import->id) }}" title="Run import"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18" height="18" width="18"><g transform="matrix(1.0714285714285714,0,0,1.0714285714285714,0,0)"><g><path d="M10.5,4.75h1a.5.5,0,0,1,.5.5v7.5a.5.5,0,0,1-.5.5h-9a.5.5,0,0,1-.5-.5V5.25a.5.5,0,0,1,.5-.5h1" style="fill: none;stroke: #000000;stroke-linecap: round;stroke-linejoin: round"></path><line x1="7" y1="0.75" x2="7" y2="8.75" style="fill: none;stroke: #000000;stroke-linecap: round;stroke-linejoin: round"></line><polyline points="5 6.75 7 8.75 9 6.75" style="fill: none;stroke: #000000;stroke-linecap: round;stroke-linejoin: round"></polyline></g></g></svg></a> &nbsp;
                        <a href="{{ cp_route('imports.destroy', $import->id) }}" title="Delete Import"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18" height="18" width="18"><g transform="matrix(1.0714285714285714,0,0,1.0714285714285714,0,0)"><g><line x1="1" y1="3.5" x2="13" y2="3.5" style="fill: none;stroke: #000000;stroke-linecap: round;stroke-linejoin: round"></line><path d="M2.5,3.5h9a0,0,0,0,1,0,0v9a1,1,0,0,1-1,1h-7a1,1,0,0,1-1-1v-9A0,0,0,0,1,2.5,3.5Z" style="fill: none;stroke: #000000;stroke-linecap: round;stroke-linejoin: round"></path><path d="M4.5,3.5V3a2.5,2.5,0,0,1,5,0v.5" style="fill: none;stroke: #000000;stroke-linecap: round;stroke-linejoin: round"></path><line x1="5.5" y1="5.5" x2="5.5" y2="11" style="fill: none;stroke: #000000;stroke-linecap: round;stroke-linejoin: round"></line><line x1="8.5" y1="5.5" x2="8.5" y2="11" style="fill: none;stroke: #000000;stroke-linecap: round;stroke-linejoin: round"></line></g></g></svg></a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
