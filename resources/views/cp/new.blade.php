@extends('statamic::layout')
@section('title', $crumbs->title('New Import'))

@section('content')
    <breadcrumbs :crumbs='@json($crumbs)'></breadcrumbs>

    <publish-form
        title="New Import"
        action="{{ cp_route('imports.store') }}"
        :blueprint='@json($blueprint)'
        :meta='@json($meta)'
        :values='@json($values)'
    ></publish-form>
@endsection
