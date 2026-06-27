@include('errors.layout',[
    'code' => '401',
    'title' => 'No autenticado',
    'message' => 'Debés iniciar sesión para acceder a esta página. Iniciá sesión y volvé a intentarlo.',
    'image' => 'img/errors/401.png'
])