<div>
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-random text-primary"></i> Gestión de Flujos de Terceros
            </h5>

            {{-- Switch Activo en cabecera --}}
            {{-- ✅ Switch de Activo al inicio --}}
            <div class="mb-4 d-flex align-items-center">
                <label class="toggle-switch">
                    <input type="checkbox" wire:model="activo">
                    <span class="toggle-slider"></span>
                </label>
                <span class="toggle-label ms-2 {{ $activo ? 'text-success fw-bold' : 'text-danger fw-bold' }}">
                    {{ $activo ? 'Activo' : 'Inactivo' }}
                </span>
            </div>
        </div>

        <div class="card-body">
            <form wire:submit.prevent="save" class="row g-3">

                {{-- Tercero --}}
                <div class="col-md-6" wire:ignore>
                    <label class="form-label">Tercero</label>
                    <select class="form-control select2" id="terceroSelect">
                        <option value="">Seleccione...</option>
                        @foreach ($terceros as $t)
                            <option value="{{ $t->id }}" {{ $tercero_id == $t->id ? 'selected' : '' }}>
                                {{ $t->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('tercero_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Aplicación --}}
                <div class="col-md-6" wire:ignore>
                    <label class="form-label">Aplicación (Sociedad)</label>
                    <select class="form-control select2" id="aplicacionSelect">
                        <option value="">Seleccione...</option>
                        @foreach ($aplicaciones as $app)
                            <option value="{{ $app->id }}" {{ $aplicacion_id == $app->id ? 'selected' : '' }}>
                                {{ $app->nombre }} ({{ $app->sociedad->nombre ?? 'Sin sociedad' }})
                            </option>
                        @endforeach
                    </select>
                    @error('aplicacion_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Usuario --}}
                <div class="col-md-6" wire:ignore>
                    <label class="form-label">Agente TI Asignado</label>
                    <select class="form-control select2" id="usuarioSelect">
                        <option value="">-- Ninguno --</option>
                        @foreach ($usuarios as $user)
                            <option value="{{ $user->id }}" {{ $usuario_id == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('usuario_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Destinatarios --}}
                <div class="col-md-6" wire:ignore>
                    <label class="form-label">Destinatarios</label>
                    <input type="text" class="form-control" id="destinatariosInput">
                    <small class="form-text text-muted">
                        Presiona <b>Enter</b> o <b>coma</b> para agregar múltiples correos.
                    </small>
                    @error('destinatarios')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Botón --}}
                <div class="col-12 text-end mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>


    {{-- Listado --}}
    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">Listado</h3>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Tercero</th>
                        <th>Aplicación</th>
                        <th>Sociedad</th>
                        <th>Agente Levadesk</th>
                        <th>Destinatarios</th>
                        <th>Activo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($flujos as $f)
                        <tr>
                            <td>{{ $f->tercero->nombre ?? '-' }}</td>
                            <td>{{ $f->aplicacion->nombre }}</td>
                            <td>{{ $f->aplicacion->sociedad->nombre ?? '-' }}</td>
                            <td>{{ $f->usuario?->name ?? '-' }}</td>
                            <td>
                                @foreach ($f->destinatarios ?? [] as $mail)
                                    <span class="badge badge-info">{{ $mail }}</span>
                                @endforeach
                            </td>
                            <td>{{ $f->activo ? 'Sí' : 'No' }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning"
                                    wire:click="edit({{ $f->id }})">Editar</button>
                                <button class="btn btn-sm btn-danger"
                                    onclick="confirmDelete({{ $f->id }})">Eliminar</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('css')
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" />
    <style>
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 26px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #dc3545;
            /* rojo cuando inactivo */
            transition: .4s;
            border-radius: 34px;
        }

        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        .toggle-switch input:checked+.toggle-slider {
            background-color: #28a745;
            /* verde cuando activo */
        }

        .toggle-switch input:checked+.toggle-slider:before {
            transform: translateX(24px);
        }

        .toggle-label {
            font-size: 16px;
        }


        .bootstrap-tagsinput {
            width: 100%;
            min-height: 38px;
            line-height: 30px;
        }

        .bootstrap-tagsinput .tag {
            margin-right: 2px;
            color: white;
            background-color: #007bff;
            padding: 3px 6px;
            border-radius: 3px;
        }

        /* ==== CARD ==== */
        .card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            background: #fff;
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid #f0f0f0;
            font-weight: 700;
            font-size: 1.05rem;
            color: #2c3e50;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card-header i {
            color: #3498db;
            font-size: 1.1rem;
        }

        /* ==== FORM ==== */
        .form-group label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #555;
            margin-bottom: 0.25rem;
        }

        .form-control,
        .select2-container--default .select2-selection--single {
            border-radius: 8px !important;
            border: 1px solid #dcdfe6 !important;
            min-height: 40px;
            padding: 6px 12px;
            font-size: 0.9rem;
            background: #fafafa;
            transition: border 0.2s ease, background 0.2s ease;
        }

        .form-control:focus,
        .select2-container--default .select2-selection--single:focus {
            border-color: #3498db !important;
            background: #fff;
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.1);
        }

        /* ==== CHIPS DESTINATARIOS ==== */
        .bootstrap-tagsinput {
            display: flex;
            flex-wrap: wrap;
            padding: 6px;
            border-radius: 8px;
            border: 1px solid #dcdfe6;
            background: #fafafa;
            min-height: 42px;
        }

        .bootstrap-tagsinput .tag {
            background: #3498db;
            color: #fff;
            border-radius: 16px;
            padding: 4px 10px;
            margin: 2px;
            font-size: 0.8rem;
            display: inline-flex;
            align-items: center;
            font-weight: 500;
        }

        /* ==== CHECKBOX ==== */
        .form-check-label {
            font-weight: 600;
            color: #2c3e50;
        }

        .form-check-input:checked {
            background-color: #3498db;
            border-color: #3498db;
        }

        /* ==== BOTÓN GUARDAR ==== */
        .btn-primary {
            border-radius: 8px;
            padding: 8px 18px;
            font-weight: 600;
            font-size: 0.9rem;
            background: linear-gradient(90deg, #3498db, #2980b9);
            border: none;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
        }
    </style>
@endpush

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>

    <script>
        document.addEventListener('livewire:load', function() {
            // Select2 inicialización
            $('#aplicacionSelect').select2().on('change', function() {
                @this.set('aplicacion_id', $(this).val());
            });
            $('#usuarioSelect').select2().on('change', function() {
                @this.set('usuario_id', $(this).val());
            });
            $('#terceroSelect').select2().on('change', function() {
                @this.set('tercero_id', $(this).val());
            });

            // TagsInput destinatarios
            let $input = $('#destinatariosInput');

            function initTags(values = []) {
                if ($input.data('tagsinput')) {
                    $input.tagsinput('destroy');
                }

                $input.tagsinput({
                    trimValue: true,
                    confirmKeys: [13, 44],
                });

                values.forEach(v => $input.tagsinput('add', v));

                $input.off('itemAdded itemRemoved').on('itemAdded itemRemoved', function() {
                    let items = $input.tagsinput('items');
                    @this.set('destinatarios', items);
                });
            }

            initTags(@json($destinatarios ?? []));

            window.addEventListener('load-tagsinput', event => {
                initTags(event.detail.destinatarios);
            });

            window.addEventListener('load-selects', event => {
                $('#aplicacionSelect').val(event.detail.aplicacion_id).trigger('change');
                $('#usuarioSelect').val(event.detail.usuario_id).trigger('change');
                $('#terceroSelect').val(event.detail.tercero_id).trigger('change');
            });

            window.addEventListener('reset-tagsinput', () => {
                initTags([]);
            });

            // 🔥 Reset completo del formulario
            window.addEventListener('reset-form', () => {
                $('#aplicacionSelect').val('').trigger('change');
                $('#usuarioSelect').val('').trigger('change');
                $('#terceroSelect').val('').trigger('change');
                $('#destinatariosInput').tagsinput('removeAll');
            });

            // 🔥 Toasts
            window.addEventListener('showToast', e => {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: e.detail.type,
                    title: e.detail.message,
                    showConfirmButton: false,
                    timer: 3000
                });
            });
        });

        // 🔥 Confirmación de eliminación
        function confirmDelete(id) {
            alertClickCallback(
                '¿Eliminar flujo?',
                'Este flujo será eliminado permanentemente',
                'warning',
                'Sí, eliminar',
                'Cancelar',
                function() {
                    @this.deleteFlujoConfirmed(id);
                }
            );
        }
    </script>
@endpush
