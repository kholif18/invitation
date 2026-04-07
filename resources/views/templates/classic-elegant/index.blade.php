@extends('templates.classic-elegant.layouts.app')

@section('content')
    <!-- Hero Section -->
    @include('templates.classic-elegant.sections.hero')
    
    <!-- Couple Section -->
    @include('templates.classic-elegant.sections.couple')
    
    <!-- Event Section -->
    @include('templates.classic-elegant.sections.event')
    
    <!-- Gallery Section -->
    @if($invitation->has_gallery)
        @include('templates.classic-elegant.sections.gallery')
    @endif
    
    <!-- Gift Section -->
    @if($invitation->has_gift)
        @include('templates.classic-elegant.sections.gift')
    @endif
    
    <!-- Maps Section -->
    @include('templates.classic-elegant.sections.map')
    
    <!-- Wish & RSVP Section -->
    @if($invitation->is_wish_active)
        @include('templates.classic-elegant.sections.wish')
    @endif
    
    <!-- Footer -->
    @include('templates.classic-elegant.sections.footer')
@endsection