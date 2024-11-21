<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg"></div>

            <div class="mt-6 bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Trabajos en Segundo Plano</h3>

                    @if(session('status'))
                        <div class="alert alert-success mb-4">
                            {{ session('status') }}
                        </div>
                    @endif

                    @isset($jobs)
                        <table class="table-auto w-full border border-gray-300">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border px-4 py-2">ID</th>
                                    <th class="border px-4 py-2">Clase</th>
                                    <th class="border px-4 py-2">Método</th>
                                    <th class="border px-4 py-2">Estado</th>
                                    <th class="border px-4 py-2">Prioridad</th> <!-- Nueva columna de prioridad -->
                                    <th class="border px-4 py-2">Intentos</th>
                                    <th class="border px-4 py-2">Iniciado</th>
                                    <th class="border px-4 py-2">Completado</th>
                                    <th class="border px-4 py-2">Error</th>
                                    <th class="border px-4 py-2">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($jobs as $job)
                                    <tr>
                                        <td class="border px-4 py-2">{{ $job->id }}</td>
                                        <td class="border px-4 py-2">{{ $job->class_name }}</td>
                                        <td class="border px-4 py-2">{{ $job->method_name }}</td>
                                        <td class="border px-4 py-2">{{ ucfirst($job->status) }}</td>
                                        <td class="border px-4 py-2">{{ $job->priority }}</td> <!-- Mostrar la prioridad -->
                                        <td class="border px-4 py-2">{{ $job->retries }}</td>
                                        <td class="border px-4 py-2">{{ $job->started_at }}</td>
                                        <td class="border px-4 py-2">{{ $job->completed_at }}</td>
                                        <td class="border px-4 py-2 text-red-600">{{ $job->error_message }}</td>
                                        <td class="border px-4 py-2">
                                            @if($job->status === 'running')
                                                <button onclick="showCancelModal({{ $job->id }})" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                                    Cancelar
                                                </button>
                                            @else
                                                <span class="text-gray-500">No disponible</span>
                                            @endif
                                        </td>
                                        <td class="border px-4 py-2">
                                            <button onclick="showDeleteModal({{ $job->id }})" class="bg-transparent text-red-500 hover:text-red-700 focus:outline-none">
                                                <i class="fas fa-trash-alt"></i> <!-- Ícono de eliminar -->
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Modal de Confirmación para Cancelar -->
                                    <div id="cancelModal{{ $job->id }}" class="modal" style="display: none;">
                                        <div class="modal-overlay" onclick="closeModal({{ $job->id }})"></div>
                                        <div class="modal-content">
                                            <h3 class="text-lg font-semibold">¿Estás seguro de cancelar este trabajo?</h3>
                                            <form action="{{ route('jobs.cancel', $job->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Sí, Cancelar</button>
                                                <button type="button" onclick="closeModal({{ $job->id }})" class="ml-4 bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">No</button>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Modal de Confirmación para Eliminar -->
                                    <div id="deleteModal{{ $job->id }}" class="modal" style="display: none;">
                                        <div class="modal-overlay" onclick="closeModal({{ $job->id }})"></div>
                                        <div class="modal-content">
                                            <h3 class="text-lg font-semibold">¿Estás seguro de eliminar este trabajo?</h3>
                                            <form action="{{ route('jobs.delete', $job->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Sí, Eliminar</button>
                                                <button type="button" onclick="closeModal({{ $job->id }})" class="ml-4 bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">No</button>
                                            </form>
                                        </div>
                                    </div>

                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-gray-500 py-4">No hay trabajos registrados.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <!-- Paginación -->
                        @if($jobs->isNotEmpty())
                            <div class="mt-4">
                                {{ $jobs->links() }}
                            </div>
                        @endif
                    @else
                        <p class="text-gray-500 py-4">No se pudo cargar la lista de trabajos.</p>
                    @endisset
                </div>
            </div>
        </div>
    </div>

    <script>
        // Mostrar el modal de cancelación
        function showCancelModal(jobId) {
            document.getElementById('cancelModal' + jobId).style.display = 'block';
        }

        // Mostrar el modal de eliminación
        function showDeleteModal(jobId) {
            document.getElementById('deleteModal' + jobId).style.display = 'block';
        }

        // Cerrar el modal
        function closeModal(jobId) {
            document.getElementById('cancelModal' + jobId).style.display = 'none';
            document.getElementById('deleteModal' + jobId).style.display = 'none';
        }
    </script>

    <style>
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .modal-content {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            width: 400px;
            text-align: center;
        }
    </style>
</x-app-layout>
