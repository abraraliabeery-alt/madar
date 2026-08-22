@extends('facility.layouts.app')

@section('title', 'تعديل عمارة')

@section('content')
<div class="container mx-auto px-4 my-10">
    <div class="w-full max-w-6xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">تعديل العمارة</h1>
        @include('facility.buildings._form')
    </div>
</div>
@endsection
