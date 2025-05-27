  const modal = document.getElementById('editarAlumno');

  modal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;

    const nombre = button.getAttribute('data-nombre');
    
    const id = button.getAttribute('data-id');
    
    // Rellenar campos
    document.getElementById('inputNombre').value = nombre;
    
    document.getElementById('idAlumnoEditar').value = id;
    
    
  });
 