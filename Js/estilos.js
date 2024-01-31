function toggleSidebar() {
    var sidebar = document.querySelector('.sidebar');
    //var sidebar = document.getElementById('.sidebar');
    //sidebar.classList.toggle('hide-sidebar');
    sidebar.classList.toggle('collapsed');

    var toggleBtn = document.getElementById('toggle-btn');
    toggleBtn.classList.toggle('active');

}

