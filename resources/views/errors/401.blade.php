@extends('errors::layout')

@section('subtitle', __('Tidak memiliki akses'))
@section('emoticon', '🔒')
@section('header', 'Stop!')
@section('message', __('Kamu tidak memiliki hak untuk mengakses halaman/tautan yang kamu maksud.'))
@section('code', 401)
