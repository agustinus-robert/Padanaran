@extends('errors::layout')

@section('subtitle', __('Terlalu banyak permintaan'))
@section('emoticon', '✈')
@section('header', 'Stop!')
@section('message', __('Terlalu banyak permintaan, coba lagi dalam beberapa menit kedepan atau hubungi kami untuk informasi lebih lanjut.'))
@section('code', 429)
