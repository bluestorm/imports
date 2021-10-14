@extends('statamic::layout')
@section('title', $crumbs->title('Edit Import'))

@section('content')
    <breadcrumbs :crumbs='@json($crumbs)'></breadcrumbs>

    <publish-form
        title="Edit Import"
        action="{{ cp_route('imports.update', $import->id) }}"
        :blueprint='@json($blueprint)'
        :meta='@json($meta)'
        :values='@json($values)'
    ></publish-form>
@endsection
