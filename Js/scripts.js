/*!
    * Start Bootstrap - SB Admin v7.0.7 (https://startbootstrap.com/template/sb-admin)
    * Copyright 2013-2023 Start Bootstrap
    * Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-sb-admin/blob/master/LICENSE)
    */
    // 
// Scripts
// 

window.addEventListener('DOMContentLoaded', event => {

    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        // Uncomment Below to persist sidebar toggle between refreshes
        // if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
        //     document.body.classList.toggle('sb-sidenav-toggled');
        // }
        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }

});

document.addEventListener("DOMContentLoaded", function() {
    interact('.draggable-box')
        .draggable({
            onmove: dragMoveHandler
        });

    // Restaurar la posición de los cuadros al cargar la página
    var boxes = document.querySelectorAll('.draggable-box');
    boxes.forEach(function(box) {
        var storedX = localStorage.getItem(box.id + "-x");
        var storedY = localStorage.getItem(box.id + "-y");

        if (storedX && storedY) {
            box.style.transform = 'translate(' + storedX + 'px, ' + storedY + 'px)';
        }
    });

    function dragMoveHandler(event) {
        var target = event.target;
        var x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx;
        var y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy;

        target.style.transform = 'translate(' + x + 'px, ' + y + 'px)';
        target.setAttribute('data-x', x);
        target.setAttribute('data-y', y);

        // Guardar las coordenadas en localStorage para el cuadro específico
        localStorage.setItem(target.id + "-x", x);
        localStorage.setItem(target.id + "-y", y);
    }
});




