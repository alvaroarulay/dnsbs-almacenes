@extends('layouts.app')
@section('content')
@if(Auth::check())
    @if (Auth::user()->idrol == 1)
    <template v-if="menu==0"><escritorio></escritorio></template>
    <template v-if="menu==1"><bbdd></bbdd></template>
    <template v-if="menu==2"><almacenes></almacenes></template>
    <template v-if="menu==3"><grupos></grupos></template>
    <template v-if="menu==4"><unidades></unidades></template>
    <template v-if="menu==5"><provedores></provedores></template>
    <template v-if="menu==6"><articulos></articulos></template>
    <template v-if="menu==7"><personal></personal></template>
    <template v-if="menu==8"><pedido></pedido></template>
    <template v-if="menu==10"><compras></compras></template>
    <template v-if="menu==11"><notaentrega></notaentrega></template>
    <template v-if="menu==12"><notasalida></notasalida></template>
    <template v-if="menu==13"><reportes></reportes></template>
    <template v-if="menu==15"><usuarios></usuarios></template>
    <template v-if="menu==16"><newuser></newuser></template>
    <template v-if="menu==17"><rol></rol></template>
    <template v-if="menu==21">
        <main class="app-content">
            <div class="row justify-content-center">
                <div class="col-lg-4">
                    <div class="card shadow-lg border-0 rounded-lg mt-12">
                        <div class="card-header">
                            <div class="logo">
                                <img src="/img/selabi.png" alt="" style="width: 200px; ">
                            </div>
                        </div>
                        <div class="card-body">
                            <p><b>Versión:</b> &nbsp; &nbsp;1.0.0.0</p>
                            <p><b>Copyright:</b> &nbsp; &nbsp;2025</p>
                            <p><b>Empresa:</b> &nbsp; &nbsp;Selabí</p>
                        </div>
                        <div class="card-footer">
                            <p><b>Advertencia:</b> &nbsp; &nbsp; Este programa está protegido por las leyes de propiedad intelectual. La reproducción o distribución ilicita de este programa, esta penado por ley y dará lugar a sanciones civiles y penales.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </template>
    @elseif (Auth::user()->idrol == 2)
   <template v-if="menu==0"><escritorio></escritorio></template>
    <template v-if="menu==1"><bbdd></bbdd></template>
    <template v-if="menu==2"><almacenes></almacenes></template>
    <template v-if="menu==3"></template>
    <template v-if="menu==4"></template>
    <template v-if="menu==5"></template>

    <template v-if="menu==21">
        <main class="app-content">
            <div class="row justify-content-center">
                <div class="col-lg-4">
                    <div class="card shadow-lg border-0 rounded-lg mt-12">
                        <div class="card-header">
                            <div class="logo">
                                <img src="/img/selabi.png" alt="" style="width: 200px; ">
                            </div>
                        </div>
                        <div class="card-body">
                            <p><b>Versión:</b> &nbsp; &nbsp;1.0.0.0</p>
                            <p><b>Copyright:</b> &nbsp; &nbsp;2023</p>
                            <p><b>Empresa:</b> &nbsp; &nbsp;ARPRO</p>
                        </div>
                        <div class="card-footer">
                            <p><b>Advertencia:</b> &nbsp; &nbsp; Este programa está protegido por las leyes de propiedad intelectual. La reproducción o distribución ilicita de este programa, esta penado por ley y dará lugar a sanciones civiles y penales.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </template><template v-if="menu==23">
        <bajas></bajas>
    </template>
@else
@endif
@else
<template>
    <main>
        <p>Sesión Expirada, por favor  <a href="{{ route('login.perform') }}">Inicie Sesión</a></p>
    </main>
</template>
<template v-if="menu==2">
<main>
        <p>Sesión Expirada, por favor  <a href="{{ route('login.perform') }}">Inicie Sesión</a></p>
    </main>
    </template>
    <template v-if="menu==23">
    <main>
        <p>Sesión Expirada, por favor  <a href="{{ route('login.perform') }}">Inicie Sesión</a></p>
    </main>
    </template>
@endif
@endsection