@extends('layouts.master-app')

@section('scripts')
    @parent
    <script src="{{ mix('js/admin.js') }}"></script>
@endsection

@section('content')
    <admin-component></admin-component>
@endsection
