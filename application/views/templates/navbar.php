<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="<?=base_url()?>dashboard" class="brand-link">
    <img src="<?=base_url()?>assets/img/logo_dinas.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">DINAS PKH</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">

    <!-- SidebarSearch Form -->
    <div class="form-inline"></div>
    

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

        <li class="nav-item">
          <a href="<?= base_url('dashboard') ?>" 
            class="nav-link <?= ($this->uri->segment(1) == 'dashboard' || $this->uri->segment(1) == '') ? 'active' : '' ?>">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= base_url('rekap') ?>" 
            class="nav-link <?= ($this->uri->segment(1) == 'rekap') ? 'active' : '' ?>">
            <i class="nav-icon fas fa-chart-bar"></i>
            <p>Rekap Data</p>
          </a>
        </li>

        <li class="nav-header">MASTER DATA</li>

        <li class="nav-item">
          <a href="<?= base_url('komoditas') ?>" 
            class="nav-link <?= ($this->uri->segment(1) == 'komoditas') ? 'active' : '' ?>">
            <i class="nav-icon fas fa-drumstick-bite"></i>
            <p>KOMODITAS</p>
          </a>
        </li>
        <!-- <li class="nav-item">
          <a href="<?= base_url('penyakit') ?>" 
            class="nav-link <?= ($this->uri->segment(1) == 'penyakit') ? 'active' : '' ?>">
            <i class="nav-icon fas fa-virus"></i>
            <p>PENYAKIT</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= base_url('layanan') ?>" 
            class="nav-link <?= ($this->uri->segment(1) == 'layanan') ? 'active' : '' ?>">
            <i class="nav-icon fas fa-hand-holding-heart"></i>
            <p>LAYANAN</p>
          </a>
        </li> -->

        <li class="nav-header">PELAPORAN</li>

        <li class="nav-item">
          <a href="<?= base_url('harga') ?>" 
            class="nav-link <?= ($this->uri->segment(1) == 'harga') ? 'active' : '' ?>">
            <i class="nav-icon fas fa-tags"></i>
            <p>HARGA KOMODITAS</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?= base_url('populasi') ?>" 
            class="nav-link <?= ($this->uri->segment(1) == 'populasi') ? 'active' : '' ?>">
            <i class="nav-icon fas fa-users"></i>
            <p>POPULASI</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?= base_url('masuk') ?>" 
            class="nav-link <?= ($this->uri->segment(1) == 'masuk') ? 'active' : '' ?>">
            <i class="nav-icon fas fa-arrow-circle-down"></i>
            <p>MASUK</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?= base_url('keluar') ?>" 
            class="nav-link <?= ($this->uri->segment(1) == 'keluar') ? 'active' : '' ?>">
            <i class="nav-icon fas fa-arrow-circle-up"></i>
            <p>KELUAR</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?= base_url('kelahiran') ?>" 
            class="nav-link <?= ($this->uri->segment(1) == 'kelahiran') ? 'active' : '' ?>">
            <i class="nav-icon fas fa-baby"></i>
            <p>KELAHIRAN</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?= base_url('kematian') ?>" 
            class="nav-link <?= ($this->uri->segment(1) == 'kematian') ? 'active' : '' ?>">
            <i class="nav-icon fas fa-skull-crossbones"></i>
            <p>KEMATIAN</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?= base_url('pemotongan') ?>" 
            class="nav-link <?= ($this->uri->segment(1) == 'pemotongan') ? 'active' : '' ?>">
            <i class="nav-icon fas fa-cut"></i>
            <p>PEMOTONGAN</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?= base_url('produksi') ?>" 
            class="nav-link <?= ($this->uri->segment(1) == 'produksi') ? 'active' : '' ?>">
            <i class="nav-icon fas fa-industry"></i>
            <p>PRODUKSI</p>
          </a>
        </li>

        
        <!-- <li class="nav-item">
          <a href="<?= base_url('penyakit_hewan') ?>" 
            class="nav-link <?= ($this->uri->segment(1) == 'penyakit_hewan') ? 'active' : '' ?>">
            <i class="nav-icon fas fa-skull-crossbones"></i>
            <p>PENYAKIT HEWAN</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= base_url('pelayanan') ?>" 
            class="nav-link <?= ($this->uri->segment(1) == 'pelayanan') ? 'active' : '' ?>">
            <i class="nav-icon fas fa-user-check"></i>
            <p>PENGGUNA PELAYANAN</p>
          </a>
        </li> -->
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
