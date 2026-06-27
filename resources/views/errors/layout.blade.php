@extends('layouts.app')

@section('title', trim($code.' - '.$title).' — KROW')

@section('banner')
<div style="
    width:100%;
    height:clamp(140px,18vw,280px);
    position:relative;
    overflow:hidden;
">

    <img src="{{ asset('img/banner-estudiante.jpg') }}"
         style="width:100%;height:100%;object-fit:cover;display:block;">

    <div style="
        position:absolute;
        inset:0;
        background:linear-gradient(to right,rgba(0,0,0,.82),rgba(0,0,0,.82));
    "></div>

</div>
@endsection


@section('content')

<style>

.error-wrapper{

    max-width:1100px;
    margin:auto;

    margin-top:clamp(-190px,-16vw,-140px);

    position:relative;
    z-index:5;
}

.error-card{

    background:var(--bg);

    border:1px solid var(--surface);

    border-radius:8px;

    text-align:center;

    padding:45px 30px 70px;
}

.error-code{

    color:var(--accent);

    font-size:110px;

    font-weight:800;

    line-height:1;
}

.error-title{

    margin-top:8px;

    font-size:42px;

    color:var(--text);

    font-weight:500;

    text-transform:uppercase;
}

.error-image{

    margin:0 auto;

    max-width:600px;
}

.error-image img{

    width:100%;
}

.error-message{

    max-width:650px;

    margin:auto;

    color:var(--muted);

    line-height:1.8;

    font-size:21px;
}

.error-button{

    margin-top:40px;
}

.error-button a{

    display:inline-block;

    padding:14px 36px;

    background:var(--accent);

    color:#111118;

    text-decoration:none;

    font-weight:700;

    transition:.25s;
}

.error-button a:hover{

    opacity:.9;
}

@media(max-width:700px){

.error-card{

padding:35px 15px 45px;

}

.error-code{

font-size:80px;

}

.error-title{

font-size:28px;

}

.error-image{

max-width:220px;

}

}

</style>


<div class="error-wrapper">

<div class="error-card">

<div class="error-code">
{{ $code }}
</div>

<div class="error-title">
{{ strtoupper($title) }}
</div>

@if(isset($image))
<div class="error-image">
<img src="{{ asset($image) }}">
</div>
@endif

<p class="error-message">
{{ $message }}
</p>

<div class="error-button">

<a href="{{ route('inicio') }}">
Volver al inicio
</a>

</div>

</div>

</div>

@endsection