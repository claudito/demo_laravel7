@extends('layouts.app')
@section('titulo')
Asistencias
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary btn-agregar" data-toggle="modal" data-target="#modal-registro">
              Agregar
            </button><br><br>
            <div class="table-responsive">
                <table class="table" id="consulta">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nombre</th>
                            <th>Fecha de Asistencia</th>
                            <th></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<form id="registro" autocomplete="off">
    <div class="modal fade" id="modal-registro" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            @csrf
            <input type="hidden" name="id" class="id">
            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="nombres" class="form-control nombres" required>
            </div>
            <div class="form-group">
                <label>Fecha de Asistencia</label>
                <input type="datetime-local" name="fecha_asistencia" class="form-control fecha_asistencia" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary btn-submit">Agregar</button>
          </div>
        </div>
      </div>
    </div>
</form>

@endsection
@section('scripts')
    <script>
        $('#consulta').DataTable({
            ajax:{
                url:'{{ route('asistencia.index') }}',
                type:'GET'
            },
            columns:[
                {data:'id'},
                {data:'nombres'},
                {data:'fecha_asistencia'},
                {data:null,render:function(data){
                    return `

                        <a  data-id="${data.id}" class="btn btn-sm btn-primary btn-edit" data-toggle="modal" data-target="#modal-registro">Editar</a>
                        <a  data-id="${data.id}" class="btn btn-sm btn-danger btn-delete">Eliminar</a>

                        `;
                }}
            ]

        });


        //Cargar Modal Agregar
        $(document).on('click','.btn-agregar',function(e){
            $('#registro')[0].reset();
            $('.id').val('');
            $('.btn-submit').html('Agregar');
            $('.modal-title').html('Agregar');
            //$('#moddal-registro').modal('show');
        });

        //Cargar Modal Actualizar
        $(document).on('click','.btn-edit',function(e){
            id = $(this).data('id');
            $('.id').val('').val(id);

            url = '{{ route('asistencia.edit',[':id']) }}';
            url = url.replace(':id',id);
            //alert( url );
            $.ajax({
                url:url,
                type:'GET',
                data:{},
                dataType:'JSON',
                success:function(data){

                    $('.nombres').val('').val(data.nombres);
                    fecha_asistencia = data.fecha_asistencia.replace(' ','T');
                    fecha            = moment(fecha_asistencia).format('YYYY-MM-DDTHH:mm');
                    $('.fecha_asistencia').val('').val(fecha);
                }
            });
            $('.btn-submit').html('Actualizar');
            $('.modal-title').html('Actualizar');
            //$('#moddal-registro').modal('show');
        });
        //registro
        $(document).on('submit','#registro',function(e){

            parametros = $(this).serialize();

            $.ajax({
                url:'{{ route('asistencia.store') }}',
                type:'POST',
                data:parametros,
                dataType:'JSON',
                beforeSend:function(){
                    Swal.fire({
                        title:'Cargando',
                        text :'Espere un momento',
                        imageUrl:'{{ asset('img/loader.gif') }}',
                        showConfirmButton:false
                    });
                },
                success:function(data){
                    Swal.fire({
                        title:data.title,
                        text :data.text,
                        icon :data.icon,
                        showConfirmButton:false
                    });
                }
            });

            e.preventDefault();
        });

        //Delete
        $(document).on('click','.btn-delete',function(e){
            id = $(this).data('id');
            Swal.fire({
              title: '¿Estás Seguro?',
              text: "EL registro se eliminará de forma permanente",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Si, estoy seguro',
              cancelButtonText:'Cancelar',
            }).then((result) => {
              if (result.isConfirmed) {
                url = '{{ route('asistencia.destroy',[':id']) }}';
                url = url.replace(':id',id)
                $.ajax({
                    url:url,
                    type:'DELETE',
                    data:{'_token':'{{ csrf_token() }}'},
                    dataType:'JSON',
                    beforeSend:function(){
                        Swal.fire({
                            title:'Cargando',
                            text :'Espere un momento',
                            imageUrl:'{{ asset('img/loader.gif') }}',
                            showConfirmButton:false
                        });
                    },
                    success:function(data){
                        Swal.fire({
                            title:data.title,
                            text :data.text,
                            icon :data.icon,
                            showConfirmButton:false
                        });
                    }
                });
              }
            });

            e.preventDefault();
        });

    </script>
@endsection
