@extends('errors.layout')

@section('code', '429')
@section('icon', 'bi bi-shield-lock text-warning')
@section('title', 'Trop de tentatives')
@section('message', 'Vous avez effectué trop de tentatives de connexion. Veuillez patienter 1 minute avant de réessayer.')
