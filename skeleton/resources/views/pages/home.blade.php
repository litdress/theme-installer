@extends('app')

@section('content')

    <div>
        <div class="_content">
            <h1>{{ $data->h1 }}</h1>
        </div>
    </div>

@endsection

@section('meta')
    <x-fj-meta-tags :metaTitle="$data->meta_title" :metaDescription="$data->meta_description" :metaKeywords="$data->meta_keywords" />
@endsection