@extends('principal')
@section('contenido')

    @if(Auth::check())
            @if (Auth::user()->idrol == 1)
            <template v-if="menu==0">
                <h1>contenido 0</h1>
            </template>

            <template v-if="menu==1">
                <categoria></categoria>
            </template>

            <template v-if="menu==2">
                <alerta></alerta>
            </template>

            <template v-if="menu==3">
                <tienda></tienda>
            </template>

            <template v-if="menu==4">
                <transporte></transporte>
            </template>

            <template v-if="menu==5">
                <envio></envio>
            </template>

            <template v-if="menu==6">
                <user></user>
            </template>

            <template v-if="menu==7">
                <rol></rol>
            </template>

            @elseif (Auth::user()->idrol == 2)

            <template v-if="menu==2">
                <alerta></alerta>
            </template>

            <template v-if="menu==5">
                <envio></envio>
            </template>

            @endif
    @endif



@endsection
