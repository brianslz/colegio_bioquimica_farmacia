<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">
            <li  @click="menu=0" class="nav-item">
                <a class="nav-link active" href="#"><i class="icon-speedometer"></i> Escritorio</a>
            </li>

            <li @click="menu=4" class="nav-item">
                <a class="nav-link" href="main.html"><i class="icon-user"></i> Mi Perfil </a>
            </li>

            <li @click="menu=5" class="nav-item">
                <a class="nav-link" href="main.html"><i class="icon-bag"></i> Mis Aportes </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#"><i class="icon-book-open"></i> Ayuda <span class="badge badge-danger">PDF</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="icon-info"></i> Acerca de...<span class="badge badge-info">CDBF</span></a>
            </li>
        </ul>
    </nav>
    <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>