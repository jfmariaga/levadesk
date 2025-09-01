<div>
    <div class="d-flex justify-content-center align-items-center vh-100 bg-light">
        <div class="card shadow-lg p-4" style="min-width: 400px;">
            <div class="card-body">
                <h3>¡Hola! Queremos saber a que área perteneces.</h3>
                <h4 class="card-title text-center mb-4">Selecciona tu área para continuar</h4>

                <div wire:ignore>
                    <select id="area" class="select2">
                        <option value="">Seleccionar...</option>
                        <option value="Administración Planta">Administración Planta</option>
                        <option value="Administrativa y Financiera">Administrativa y Financiera</option>
                        <option value="Auditoría">Auditoría</option>
                        <option value="Cadena de Abastecimiento">Cadena de Abastecimiento</option>
                        <option value="Comercial Biolev">Comercial Biolev</option>
                        <option value="Comercial Consumo">Comercial Consumo</option>
                        <option value="Comercial Exportaciones">Comercial Exportaciones</option>
                        <option value="Comercial Panadería">Comercial Panadería</option>
                        <option value="Comercio Exterior">Comercio Exterior</option>
                        <option value="Compras">Compras</option>
                        <option value="Contabilidad e Impuestos">Contabilidad e Impuestos</option>
                        <option value="Control Calidad">Control Calidad</option>
                        <option value="Control Financiero">Control Financiero</option>
                        <option value="Departamento Técnico">Departamento Técnico</option>
                        <option value="Desarrollo de Negocios">Desarrollo de Negocios</option>
                        <option value="Gente y Cultura">Gente y Cultura</option>
                        <option value="Gestión Integral">Gestión Integral</option>
                        <option value="Gestión Medioambiental">Gestión Medioambiental</option>
                        <option value="Go To Market">Go To Market</option>
                        <option value="Investigación y Desarrollo">Investigación y Desarrollo</option>
                        <option value="Legal">Legal</option>
                        <option value="Logística">Logística</option>
                        <option value="Mantenimiento">Mantenimiento</option>
                        <option value="Mejora continua y proyectos">Mejora continua y proyectos</option>
                        <option value="Mercadeo">Mercadeo</option>
                        <option value="Planeación de la Demanda">Planeación de la Demanda</option>
                        <option value="Planeación Financiera">Planeación Financiera</option>
                        <option value="Producción">Producción</option>
                        <option value="Servicios Administrativos">Servicios Administrativos</option>
                        <option value="Tecnología">Tecnología</option>
                    </select>
                    @error('area')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>


                <div class="d-flex justify-content-center mt-4">
                    <button class="btn btn-primary w-100" wire:click="guardar()" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="guardar">Guardar área</span>
                        <span wire:loading wire:target="guardar">
                            <i class="la la-refresh spinner"></i> Guardando...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @push('js')
        <script>
            $('.select2').select2();

            $('#area').on('change', function() {
                @this.set('area', $(this).val());
            });
        </script>
    @endpush
</div>
