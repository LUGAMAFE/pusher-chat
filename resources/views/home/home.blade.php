@extends('layouts.master-app')

@section('scripts')
    @parent
    <script src="{{ mix('js/chat.js') }}"></script>
@endsection

@section('content')
    <chat-component></chat-component>
@endsection
