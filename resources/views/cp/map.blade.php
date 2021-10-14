@extends('statamic::layout')
@section('title', $crumbs->title('Map Fields'))

@section('content')
    <breadcrumbs :crumbs='@json($crumbs)'></breadcrumbs>

    {!! Form::model($import, ['route' => ['statamic.cp.imports.storeMappedFields', $import->id], 'method' => 'post']) !!}
    {!! Form::token() !!}

    <div class="flex items-center justify-between mb-3">
        <h1>{{ $import->name }}</h1>

        <button type="submit" class="btn btn-primary">
            Save Data
        </button>
    </div>

    <div class="card p-0">
        <table class="data-table">
            <thead>
            <tr>
                <th>Collection Fields</th>
                <th>Import Fields</th>
                <th>Unique Identifier</th>
            </tr>
            </thead>
            <tbody>
                @foreach ($collectionFields as $field)
                    <tr>
                        <td>{{ $field }}</td>
                        <td>
                            <select name="{{ $field }}">
                                <option value="">Please select</option>
                                @foreach ($headers as $value)
                                    <option value="{{ $value }}" {{ isset($fieldMapping[$field]) && $fieldMapping[$field] == $value ? 'selected="selected"' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>{!! Form::radio('fieldUnique', $field); !!}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {!! Form::close() !!}
@endsection
